<?php

namespace App\Services\ProductService;

use Illuminate\Http\JsonResponse;


interface IProductService
{
    public function getAll() : JsonResponse;


    public function getProduct(string $code) : JsonResponse;
     

    public function updateProduct(array $productData, string $code) : JsonResponse;
    

    public function deleteProduct(string $code) : JsonResponse;
}
