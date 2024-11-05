<?php

namespace App\Services\ProductService;

use App\ApiResponses;
use App\Enums\ProductStatus;
use App\Exceptions\CachingConnectionException;
use App\Models\Product;
use App\Services\CachingService\ICachingService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Log\LogManager;

class ProductService implements IProductService
{

    
    //Trait contendo as respostas
    use ApiResponses;


    public function __construct(
        protected ICachingService $cachingService,
        protected LogManager  $loggerService
    ){}

    public function getAll($page=null) : JsonResponse{
        try{
            //Offset padrão
            $offset = $page ?? 1;

            //Pegar de 10 em 10 por página
            $productCollection = Product::skip($offset-1)->take(10)->get();

            $query = [
                "code"=>"none",
                'page'=>$offset
            ];

            return $this->productResponse($query,$productCollection,Product::count());
        }catch(Exception $e){
            return $this->badGatewayProductResponse("none");
        }
    }


    public function getProduct(string $code) : JsonResponse{
        try{
            //Query para response
            $query = [
                "code"=>$code,
            ];
            
            //Tentativa de Busca no Redis
            $product = $this->tryGetFromCache($code);

            //Caso não encontre, tentativa de Busca na base de dados
            if(!$product){
                $product = Product::where("code", $code)->firstOrFail();

                //Salva na base de dados
                $this->cachingService->set($code,$product);
            }
            return $this->productResponse($query,[$product],1);
        }catch(ModelNotFoundException $e){
            $this->loggerService->info($e->getMessage());
            return $this->notFoundProductResponse($code);
        }catch(Exception $e){
            return $this->badGatewayProductResponse($code);
        }
    }

    public function updateProduct(array $productData, string $code) : JsonResponse{
        try{
            //Query para response
            $query = [
                "code"=>$code
            ];

            //Atualizar dados durante importação
            $productData['status'] = ProductStatus::PUBLISHED;
            $productData['imported_t'] = now();
            
            Product::updateOrCreate(['code' => $code],$productData);

            //Tentativa de Invalidação de Produto no Cache no Redis
            $this->invalidateProductCache($code);
            
            return $this->productResponse($query,[$productData],1,HttpResponse::HTTP_ACCEPTED);
        }catch(Exception $e){
            echo $e->getMessage();
            return $this->badGatewayProductResponse($code);
        }
    }

    public function deleteProduct(string $code) : JsonResponse{
        try{
            //Query
            $query = [
                "code"=>$code,
                "update_status"=>ProductStatus::TRASH
            ];

            //Atualização na Base de Dados
            $product = Product::where("code", $code)->firstOrFail();
            $product->status = ProductStatus::TRASH;
            $product->save();

            //Tentativa de Invalidação de Produto no Cache no Redis
            $this->invalidateProductCache($code);

            return $this->productResponse($query,[$product],1,HttpResponse::HTTP_GONE);
        }catch(ModelNotFoundException $e){
            $this->loggerService->info($e->getMessage());
            return $this->notFoundProductResponse($code);
        }catch(Exception $e){
            return $this->badGatewayProductResponse($code);
        }
    }

    //Busca de Produto pela Id no Cache
    private function tryGetFromCache($code){
        try{
            return json_decode($this->cachingService->get($code));
        }catch(CachingConnectionException $e){
            return false;
        }
    }

    //Inavlidar Produto no Cache
    private function invalidateProductCache($code){
            //Invalidação de Produto no Cache no Redis
            try{
                $this->cachingService->invalidate($code);
            }catch(CachingConnectionException $e){
                //Mandar para uma fila talvez e tentar posteriormente a invalidação
                //Criar uma blacklist para evitar busca no cache enquanto isso
            }
        
    }
}
