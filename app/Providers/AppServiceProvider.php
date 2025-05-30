<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MercadoPagoServices;
use App\Services\MercadoPagoServicesInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        $this->app->bind(MercadoPagoInterface::class, MercadoPagoServices::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
