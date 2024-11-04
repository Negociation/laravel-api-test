<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\CachingServiceProvider::class,
    App\Providers\ProductServiceProvider::class,
    App\Providers\RedisServiceProvider::class,
    MongoDB\Laravel\MongoDBServiceProvider::class,
];
