<?php

namespace App\Services\CachingService;

interface ICachingService {
    
    //Recupera um dado pela chave
    public function get($key);

    //Adiciona um novo elemento
    public function set($key,$valor);
    
    //Invalida o cache de um dado
    public function invalidate($key);

    //Reseta o Cache na totalidade
    public function reset();
}