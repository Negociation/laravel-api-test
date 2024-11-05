<?php

namespace App;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponses
{
 // Response de Produto Encontrado
    protected function productResponse($query, $productCollection,$totalFound, $statusCode = Response::HTTP_OK): JsonResponse
    {
        $result = [
            'query' => $query,
            'status' => true,
            'statusCode' => $statusCode,
            'product_directory' => [
                'total_found' => $totalFound,
                'products' => $productCollection
            ]
        ];

        return response()->json($result, $statusCode);
    }

    // Response de Produto não encontrado
    protected function notFoundProductResponse($code): JsonResponse
    {
        $result = [
            'query' => ['code' => $code],
            'status' => false,
            'statusCode' => Response::HTTP_NOT_FOUND,
            'error' => 'Não foi possível encontrar um produto com a id especificada.'
        ];

        return response()->json($result, Response::HTTP_NOT_FOUND);
    }

    // Response de Exception Interna
    protected function badGatewayProductResponse($code): JsonResponse
    {
        $result = [
            'query' => ['code' => $code],
            'status' => false,
            'statusCode' => Response::HTTP_BAD_GATEWAY,
            'error' => 'Erro durante a execução, tente novamente mais tarde.'
        ];

        return response()->json($result, Response::HTTP_BAD_GATEWAY);
    }

}
