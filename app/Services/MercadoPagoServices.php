<?php

namespace App\Services;

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Order\OrderClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoServices implements MercadoPagoServicesInterface
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN='));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL); // O PRODUCTION
    }

    public function crearOrden(array $datos)
    {
        $client = new OrderClient();

        try {
            $request = [
                "external_reference" => $datos['referencia'],
                "title" => $datos['titulo'],
                "quantity" => 1,
                "unit_price" => $datos['precio'],
                "payer" => [
                    "email" => $datos['email'],
                ]
            ];

            $request_options = new RequestOptions();
            $request_options->setCustomHeaders([
                "X-Idempotency-Key: " . uniqid("orden_", true)
            ]);

            $order = $client->create($request, $request_options);
            return $order;

        } catch (MPApiException $e) {
            \Log::error("Error MercadoPago API: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            \Log::error("Error general MercadoPago: " . $e->getMessage());
            throw $e;
        }
    }
}

