<?php

namespace App\Services\ProductService;

use Illuminate\Http\JsonResponse;


interface IProductService
{
    public function getAll() : JsonResponse;


    public function getProduct(int $code) : JsonResponse;
     

    public function updateProduct(array $productData, int $code) : JsonResponse;
    

    public function deleteProduct(int $code) : JsonResponse;
}
