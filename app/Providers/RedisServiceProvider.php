<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Predis\Client;

class RedisServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function(){
            return new Client([
                'scheme' => 'tcp',
                'host' => env('REDIS_HOST', 'redis'),
                'port' => env('REDIS_PORT', 6379)
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
