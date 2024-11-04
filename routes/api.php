<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Rota de Health
Route::get('/', [ApiController::class, 'healthData']);

//Grupo de Rotas
Route::group(['prefix' => 'products', 'middleware' => ['api','api.auth']], function () {
    
    /* Definindo Constante para evitar repetição */
    $urlSufix = "/{code}";

    //Rotas
    Route::get('/', [ProductController::class, 'getAllProducts']);
    Route::put($urlSufix,[ProductController::class, 'updateProductData']);
    Route::delete($urlSufix,[ProductController::class, 'deleteProduct']);
    Route::get($urlSufix,[ProductController::class, 'getProductData']);
})->middleware(ApiAuthMiddleware::class);