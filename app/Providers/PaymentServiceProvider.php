<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MercadoPagoInterface;
use App\Services\MercadoPagoServices;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(MercadoPagoInterface::class, MercadoPagoServices::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
