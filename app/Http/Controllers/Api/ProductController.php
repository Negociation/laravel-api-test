<?php

namespace App\Http\Controllers\Api;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductUpdatePutRequest;
use App\Services\ProductService\IProductService;
use Illuminate\Log\Logger;
use OpenApi\Annotations as OA;

//Mover para Definitions depois

/**
* @OA\Info(
    *     version="1.0.0",
    *     title="Api Produtos e Nutrição",
    *     description="Exemplo Pratico",
    *     @OA\Contact(name="Marcos França")
    * )
*/
class ProductController extends Controller{

    public function __construct(
        private IProductService $productService,
        private Logger $loggerService
    ){}

    //Trait contendo as respostas
    use ApiResponses;

    //Recuperar todos os produtos
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Recupera todos os produtos",
     *     description="Este endpoint recupera uma lista de todos os produtos disponíveis no sistema.",
     *     tags={"Produtos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos recuperada com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="none"),
     *                 @OA\Property(property="page", type="int", example="0")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="product_directory", type="object",
     *                 @OA\Property(property="total_found", type="integer", example=0),
     *                 @OA\Property(property="products", type="array",
     *                     @OA\Items(type="object")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=502,
     *         description="Erro ao tentar recuperar produtos. Este erro pode ocorrer se houver problemas de conexão com o servidor de banco de dados ou serviço de cache.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="statusCode", type="integer", example=502),
     *             @OA\Property(property="error", type="string", example="Erro durante a execução, tente novamente mais tarde.")
     *         )
     *     ),
     * )
     */
    public function getAllProducts(){
        return $this->productService->getAll();
    }

    //Recuperar um Produto
    /**
     * @OA\Get(
     *     path="/api/products/{code}",
     *     summary="Recupera um produto pela index code",
     *     description="Este endpoint recupera os detalhes de um produto específico usando seu código (ID) fornecido na URL.",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="Código do produto que você deseja recuperar.",
     *         @OA\Schema(type="integer", example="12345")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto recuperado com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="product_directory", type="object",
     *                 @OA\Property(property="total_found", type="integer", example=1),
     *                 @OA\Property(property="products", type="array",
     *                     @OA\Items(type="object")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado. O código fornecido não corresponde a nenhum produto existente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="statusCode", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Não foi possível encontrar um produto com a id especificada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=502,
     *         description="Erro ao tentar recuperar o produto. Este erro pode ocorrer se houver problemas de conexão com o servidor de banco de dados ou serviço de cache.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="statusCode", type="integer", example=502),
     *             @OA\Property(property="error", type="string", example="Erro durante a execução, tente novamente mais tarde.")
     *         )
     *     )
     * )
     */
    public function getProductData($code){

        if($this->validateCodePattern($code)){
            return $this->productService->getProduct($code);
        }

        return $this->notFoundProductResponse($code);
    }

    //Atualizar Produto
    /**
     * @OA\Put(
     *     path="/api/products/{code}",
     *     summary="Atualiza um produto",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="O código do produto",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
    *               required={"code", "url", "creator", "created_t", "status", "last_modified_t", "product_name", "quantity", "brands", "categories", "labels", "cities", "purchase_places", "stores", "ingredients_text", "traces", "serving_size", "serving_quantity", "nutriscore_score", "nutriscore_grade", "main_category", "image_url"},
    *               @OA\Property(property="code", type="string", example="12345", description="Código do produto"),
    *               @OA\Property(property="url", type="string", example="https://example.com/produto/12345", description="URL do produto"),
    *               @OA\Property(property="creator", type="string", example="admin", description="Criador do produto"),
    *               @OA\Property(property="created_t", type="string", format="date-time", example="2024-11-01 12:34:56", description="Data de criação"),
    *               @OA\Property(property="status", type="string", enum={"draft", "trash", "published"}, example="published", description="Status do produto"),
    *               @OA\Property(property="last_modified_t", type="string", format="date-time", example="2024-11-01 12:34:56", description="Data da última modificação"),
    *               @OA\Property(property="product_name", type="string", example="Produto Exemplo", description="Nome do produto"),
    *               @OA\Property(property="quantity", type="string", example="100g", description="Quantidade do produto"), 
    *               @OA\Property(property="brands", type="string", example="Marca Exemplo", description="Marca do produto"),
    *               @OA\Property(property="categories", type="string", example="Alimentos", description="Categorias do produto"),
    *               @OA\Property(property="labels", type="string", example="Orgânico, Sem Glúten", description="Rótulos do produto"),
    *               @OA\Property(property="cities", type="string", example="São Paulo, Rio de Janeiro", description="Cidades onde o produto é vendido"),
    *               @OA\Property(property="purchase_places", type="string", example="Supermercado Exemplo", description="Locais de compra"),
    *               @OA\Property(property="stores", type="string", example="Loja Exemplo", description="Lojas que vendem o produto"),
    *               @OA\Property(property="ingredients_text", type="string", example="Açúcar, Farinha de Trigo, Óleo Vegetal", description="Ingredientes do produto"),
    *               @OA\Property(property="traces", type="string", example="Amendoim, Soja", description="Traços de alérgenos"),
    *               @OA\Property(property="serving_size", type="string", example="30g", description="Tamanho da porção"),
    *               @OA\Property(property="serving_quantity", type="string", example="30", description="Quantidade por porção"),
    *               @OA\Property(property="nutriscore_score", type="integer", example=12, description="Pontuação NutriScore"),
    *               @OA\Property(property="nutriscore_grade", type="string", example="A", description="Grau NutriScore"),
    *               @OA\Property(property="main_category", type="string", example="Bebidas", description="Categoria principal do produto"),
    *               @OA\Property(property="image_url", type="string", example="https://example.com/imagem.jpg", description="URL da imagem do produto")
    *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Produto atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126"),
     *             ),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="product_directory", type="object",
     *                 @OA\Property(property="total_found", type="integer", example=0),
     *                 @OA\Property(property="products", type="array",
     *                     @OA\Items(type="object")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="statusCode", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Não foi possível encontrar um produto com a id especificada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=502,
     *         description="Erro ao tentar atualizar o produto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126"),
     *                 @OA\Property(property="product", type="object")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="statusCode", type="integer", example=502),
     *             @OA\Property(property="error", type="string", example="Erro durante a execução, tente novamente mais tarde.")
     *         )
     *     ),
     * )
     */
    public function updateProductData(ProductUpdatePutRequest $request,$code){
        $productData = $request->all();

        if($this->validateCodePattern($code)){
            return $this->productService->updateProduct($productData,$code);
        }

        return $this->notFoundProductResponse($code);
    }



    //Deletar Produto
    /**
     * @OA\Delete(
     *     path="/api/products/{code}",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="O código do produto",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Produto Deletado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126"),
     *                 @OA\Property(property="update_status", type="string", example="trash")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="product_directory", type="object",
     *                 @OA\Property(property="total_found", type="integer", example=0),
     *                 @OA\Property(property="products", type="array",
     *                     @OA\Items(type="object")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="statusCode", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Não foi possível encontrar um produto com a id especificada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=502,
     *         description="Erro ao tentar deletar o produto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="query", type="object",
     *                 @OA\Property(property="code", type="string", example="20221126"),
     *                 @OA\Property(property="product", type="object")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="statusCode", type="integer", example=502),
     *             @OA\Property(property="error", type="string", example="Erro durante a execução, tente novamente mais tarde.")
     *         )
     *     )
     * )
     */
    public function deleteProduct($code){
        if($this->validateCodePattern($code)){
            return $this->productService->deleteProduct($code);
        }
        return $this->notFoundProductResponse($code);
    }

    //Validação de Pattern com Regex
    private function validateCodePattern($code){

        if (!preg_match('/^-?\d+$/', $code)) {
            return false;
        }
        return true;
    }
}
