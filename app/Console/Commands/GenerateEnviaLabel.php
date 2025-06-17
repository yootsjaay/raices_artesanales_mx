<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EnviaService;

class GenerateEnviaLabel extends Command
{
    protected $signature = 'envia:generate-label';
    protected $description = 'Generates an Envia.com shipping label with a predefined payload.';
    protected $enviaService;

    public function __construct(EnviaService $enviaService)
    {
        parent::__construct();
        $this->enviaService = $enviaService;
    }

    public function handle()
    {
        $this->info('Generando etiqueta de envio...');

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
        "city" => "San Pedro Garza García",
        "state" => "NL",
        "phone_code" => "MX",
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
        "city" => "Monterrey",
        "state" => "NL",
        "phone_code" => "MX",
        "reference" => "389"
    ],
    "packages" => [
        [
            "type" => "box",
            "content" => "camisetas rojas",
            "amount" => 1,
            "weight" => 5,
            "declaredValue" => 400,
            "weightUnit" => "KG",
            "lengthUnit" => "CM",
            "dimensions" => [
                "length" => 46,
                "width" => 30,
                "height" => 20
            ],
            "insurance" => 0
        ]
    ],
    "shipment" => [
        "carrier" => "dhl",
        "service" => "ground",
        "type" => 1,
        "currency" => "MXN"
    ],
    "settings" => [
        "printFormat" => "PDF",
        "printSize" => "STOCK_4X6",
        "comments" => "Envío de camisetas rojas"
    ]
];
\Log::debug('Payload para Envia:', $data);


        

        try {
            $response = $this->enviaService->createShipment($data);

            if ($response) {
                $this->info('Shipping label generated successfully!');
                $this->comment("------------------------------------");

                if (isset($response['data'])) {
                    $shipment = $response['data'];
                    $this->comment("🆔 Order ID: " . ($shipment['order_id'] ?? 'N/A'));
                    $this->comment("📦 Tracking Number: " . ($shipment['tracking_number'] ?? 'N/A'));
                    $this->comment("🔗 Label URL: " . ($shipment['label_url'] ?? 'N/A'));
                } else {
                    $this->warn('La respuesta no contiene el campo "data".');
                    $this->line('Respuesta completa: ' . json_encode($response, JSON_PRETTY_PRINT));
                }

                $this->comment("------------------------------------");
                return Command::SUCCESS;
            } else {
                $this->error('Failed to generate shipping label with Envia.com.');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Error inesperado al generar la guía: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
