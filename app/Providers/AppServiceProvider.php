<?php

namespace App\Providers;

use App\Services\BerkasService;

use App\Services\QRCodeService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BerkasService::class, function ($app) {
            return new BerkasService();
        });

        // Register QRCodeService
        $this->app->singleton(QRCodeService::class, function ($app) {
            return new QRCodeService();
        });

        Paginator::useBootstrapFive();
    }

    public function boot()
    {
        //
    }
}
