<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiProductsCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:api-product-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            //Download da Lista de Colleção
            if(!$collectionList = file_get_contents('https://challenges.coode.sh/food/data/json/index.txt')){
                throw new Exception("Não foi possivel fazer o download da lista de elementos");
            }

            //Separação dos Itens da Lista
            $jsonFiles = explode("\n", $collectionList);

            //Loop através de cada arquivo
            foreach($jsonFiles as $filename){

                //Download do Arquivo original URL
                $url = "https://challenges.coode.sh/food/data/json/{$filename}";


                //Verificação de status do download
                if(($file = @file_get_contents($url)) === false){
                    //No caso de erro ao recuperar, tentar a proxima interação
                    continue;
                }

                //Diretorio de Destino do aquivo apos o download
                $destinationPath = storage_path('json'.DIRECTORY_SEPARATOR . $filename);

                //Se não houve mudança pelo checksum, pular interação
                //Ideal seria Mover para collection com historico baseado em checksum
                if($this->validateChecksum($destinationPath, $file)){
                    continue;
                }

                //Salvar conteudo do Arquivo
                file_put_contents($destinationPath, $file);

                $gzipHandler = gzopen($destinationPath, 'rb');
                
                //Coleção para ser atualizada
                $productCollectionToBeExported = [];

                //Recuperar os 100 objetos de cada arquivo
                //Se necessario criar paginação ao longo do dia
                for ($line = 0; $line < 100; $line++) {
                    $productObject = gzgets($gzipHandler);
                    if ($line === false) {
                        break;
                    }
                    array_push($productCollectionToBeExported, json_decode($this->sanitizeJson($productObject),true));
                }

                //Enviar Items para Rota
                $this->importCollection($productCollectionToBeExported);

                //Limpeza de Memoria
                unset($file);
                gzclose($gzipHandler);
            }
        }catch(Exception $e){
            //Exportar Logs pensando em Observabilidade
            Log::error($e->getMessage());
            $this->info($e->getMessage());
        }
    }


    //Funções Auxiliares

    /**
     * Valida se o checksum do arquivo original é igual ao do novo arquivo.
     *
     * @param string $originalFile Caminho do arquivo original a ser verificado.
     * @param string $newFile Dados do Arquivo recuperado para comparação.
     * @return bool Retorna true se o arquivo original existe e os checksums são iguais ou false se o arquivo não existe ou os checksums não coincidem.
     */
    private function validateChecksum($originalFile,$newFile) : bool{
        //Se o arquivo não existe basta continuar, caso contrario necessidade de teste
        if(file_exists($originalFile)){
            return md5_file($originalFile) == md5($newFile);
        }
        return false;
    }

    /**
     * Sanitiza uma string JSON removendo padrões indesejados e corrigindo formatações.
     *
     * @param string $json String JSON que será sanitizada.
     * @return string A string JSON sanitizada, apos regex.
     */
    private function sanitizeJson($json) : string{
        $patterns = [
            '/;{10,}/',               // Remove 10 ou mais ponto e vírgula
            '/"\\\\/',                // Remove a sequência \"
            '/:r"/',                  // Corrige a sequência :r"
            '/}\s*{/m',               // Substitui } { por },
        ];
        
        $replacements = [
            '',                       // Para o primeiro padrão, substitui por nada
            '',                       // Para o segundo padrão, substitui por nada
            ':""',                    // Para o terceiro padrão
            '},{',                    // Para o quarto padrão
        ];
        return preg_replace($patterns, $replacements, $json);
    }


    /**
     * Importa uma coleção de produtos.
     *
     * @param array $productCollection Coleção de produtos a ser importada.
     * @return void
     */
    private function importCollection($productCollection) : void{
        foreach($productCollection as $product){
    
            $response = Http::withToken(env('API_KEY'))->put(route('products.update',['code' => $product['code']]), $product);
            
            if(($statusCode = $response->status()) != Response::HTTP_ACCEPTED){
                $msg = "Falha ao importar Produto {$product['code']}. Código Http  {$statusCode}";
                Log::info($msg);
                $this->info($msg);
                return;
            }
            $msg = "Produto {$product['code']} importado com sucesso";
            Log::info($msg);
            $this->info($msg);
        }
    }
}
