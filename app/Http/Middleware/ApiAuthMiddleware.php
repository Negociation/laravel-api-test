<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->bearerToken();

        if($apiKey != env("API_KEY")){
            $response = [
                "status"=>false,
                "statusCode"=>HttpResponse::HTTP_UNAUTHORIZED,
                "error"=>"Você não possui autorização para acessar este recurso."
            ];
            return response()->json($response,HttpResponse::HTTP_UNAUTHORIZED);
        }
        
        return $next($request);
    }
}
