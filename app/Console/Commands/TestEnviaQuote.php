<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EnviaService;

class TestEnviaQuote extends Command
{protected $signature = 'envia:test-quote';
    protected $description = 'Tests the Envia.com shipping quotation API with a predefined payload.';
    protected $enviaService;

    public function __construct(EnviaService $enviaService)
    {
        parent::__construct();
        $this->enviaService = $enviaService;
    }

    public function handle()
    {
        $this->info('Intentando obtener una cotización de envío de Envia.com con nuevos datos...');

        $data = [
            "origin" => [
                "number" => "1400",
                "postalCode" => "66236",
                "type" => "origin",
                "company" => "enviacomMarcelo",
                "name" => "Vasconcelos",
                "email" => "test@envia.com",
                "phone" => "8180808080",
                "country" => "MX",
                "street" => "Vasconcelos",
                "district" => "Jardines de Mirasierra",
                "city" => "San Pedro Garza García",
                "state" => "NL",
                "phone_code" => "MX",
                "category" => 1,
                "identificationNumber" => "389",
                "reference" => ""
            ],
            "destination" => [
                "number" => "2470 of 310",
                "postalCode" => "64060",
                "type" => "destination",
                "company" => "Tendencys",
                "name" => "Marcelo Gomez",
                "email" => "test@envia.com",
                "phone" => "8180808080",
                "country" => "MX",
                "street" => "Belisario Dominguez",
                "district" => "Obispado",
                "city" => "Monterrey",
                "state" => "NL",
                "phone_code" => "MX",
                "category" => 1,
                "identificationNumber" => "389",
                "reference" => "389"
            ],
            "packages" => [
                [
                    "type" => "envelope",
                    "content" => "Accesorios",
                    "amount" => 1,
                    "name" => "Paquete Capacitacion",
                    "declaredValue" => 2000,
                    "lengthUnit" => "CM",
                    "weightUnit" => "KG",
                    "weight" => 0.5,
                    "dimensions" => [
                        "length" => 20,
                        "width" => 2,
                        "height" => 15
                    ],
                    "additionalServices" => [
                        [
                            "data" => [
                                "amount" => 2000
                            ],
                            "service" => "cash_on_delivery"
                        ],
                        [
                            "data" => [
                                "amount" => 2000
                            ],
                            "service" => "insurance"
                        ]
                    ]
                ]
            ],
            "settings" => [
                "printFormat" => "PDF",
                "printSize" => "STOCK_4X6",
                "currency" => "MXN",
                "comments" => ""
            ],
            "shipment" => [
                "type" => 1,
                "import" => 0,
                 "carrier" => "dhl", 
            ]
        ];

        $quote = $this->enviaService->quoteShipping($data);

        if ($quote) {
            $this->info('Cotizaciones recibidas:');
            if (isset($quote['data']) && is_array($quote['data'])) {
                foreach ($quote['data'] as $option) {
                $this->comment("------------------------------------");
                $this->comment("Proveedor: " . ($option['carrier'] ?? 'N/A'));
                $this->comment("Descripción: " . ($option['serviceDescription'] ?? 'N/A'));
                $this->comment("Servicio: " . ($option['service'] ?? 'N/A'));
                $this->comment("Costo Total: " . ($option['totalPrice'] ?? 'N/A') . " " . ($option['currency'] ?? 'N/A'));
                $this->comment("Tiempo estimado: " . ($option['deliveryEstimate'] ?? 'N/A'));

                }
                $this->comment("------------------------------------");
            } else {
                $this->warn('La respuesta no contiene el campo "data" o está vacía.');
                $this->line('Respuesta completa: ' . json_encode($quote, JSON_PRETTY_PRINT));
            }
            return Command::SUCCESS;
        } else {
            $this->error('No se pudo obtener una cotización de envío de Envia.com.');
            $this->error('Por favor, revisa los logs de Laravel (storage/logs/laravel.log).');
            return Command::FAILURE;
        }
    }
}