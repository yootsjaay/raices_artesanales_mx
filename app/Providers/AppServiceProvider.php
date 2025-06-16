<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\EnviaService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EnviaService::class, EnviaService::class);
       // $this->app->bind(MercadoPagoInterface::class, MercadoPagoServices::class);
       //$this->app->bind(EnviaService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
