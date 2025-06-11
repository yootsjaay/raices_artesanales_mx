<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SkydropxService;
use Illuminate\Support\Facades\Log;

class TestSkydropxQuotation extends Command
{
    protected $signature = 'skydropx:test';
    protected $description = 'Prueba de generación de token y cotización con SkydropX';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(SkydropxService $skydropx)
    {
        try {
            $this->info('Obteniendo token de acceso de SkydropX...');
            $reflection = new \ReflectionClass($skydropx);
            $method = $reflection->getMethod('getAccessToken');
            $method->setAccessible(true);
            $token = $method->invoke($skydropx);

            $this->info("Token obtenido: {$token}");

            $this->info("Generando cotización de prueba...");

            // Datos de ejemplo
            $addressFrom = [
                'zip_code' => '01000', // CDMX
                'country' => 'MX',
            ];

            $addressTo = [
                'zip_code' => '20000', // Aguascalientes
                'country' => 'MX',
            ];

            $parcel = [
                'weight' => 1,
                'height' => 10,
                'width' => 10,
                'length' => 10,
            ];

            $quotation = $skydropx->createQuotation($addressFrom, $addressTo, $parcel);

            $this->info("Cotización recibida:");
            $this->line(json_encode($quotation, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->error("Error al probar SkydropX: " . $e->getMessage());
            Log::error("SkydropX Test Error", ['exception' => $e]);
        }
    }
}
