<?php

namespace App\Providers;

use App\Services\CachingService\CachingService;
use App\Services\CachingService\ICachingService;
use Illuminate\Support\ServiceProvider;
use Predis\Client;
use Psr\Log\LoggerInterface;

class CachingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ICachingService::class, function ($app) {
            return new CachingService($app[Client::class],$app[LoggerInterface::class]);
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
