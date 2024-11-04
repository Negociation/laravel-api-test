<?php

namespace App\Providers;

use App\Services\CachingService\ICachingService;
use App\Services\ProductService\IProductService;
use App\Services\ProductService\ProductService;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IProductService::class, function ($app) {
            return new ProductService($app[ICachingService::class],$app[LoggerInterface::class]);
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
