<?php

namespace App\Services\CachingService;

use App\Exceptions\CachingConnectionException;
use Exception;
use Predis\Client;
use Psr\Log\LoggerInterface;

class CachingService implements ICachingService{

    public function __construct(private Client $redisClient, private LoggerInterface $loggerService){}

    
    public function get($key){
        try{
            return $this->redisClient->get($key);
        }catch(Exception $e){
            $this->loggerService->error('Erro ao recuperar elemento do Redis: ' . $e->getMessage());
            echo $e->getMessage();
            throw new CachingConnectionException($e->getMessage());
        }
    }

    public function set($key,$valor){
        try{
            return $this->redisClient->set($key, $valor);
        }catch(Exception $e){
            $this->loggerService->error('Erro ao inserir elemento no Redis: ' . $e->getMessage());
            throw new CachingConnectionException($e->getMessage());
        }
    }

    public function invalidate($key){
        try{
            if($this->redisClient->exists($key)){
                $this->redisClient->del($key);
                return true;
            }
            return false;
        }catch(Exception $e){
            $this->loggerService->error('Erro ao invalidar elemento do Redis: ' . $e->getMessage());
            throw new CachingConnectionException($e->getMessage());
        }
       
    }

    public function reset(){
        try{
            return $this->redisClient->flushdb();
        }catch(Exception $e){
            $this->loggerService->error('Erro ao resetar o Redis: ' . $e->getMessage());
            throw new CachingConnectionException($e->getMessage());
        }
    }
}