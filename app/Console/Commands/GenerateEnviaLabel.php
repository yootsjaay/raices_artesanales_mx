<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EnviaService;
use Illuminate\Support\Facades\Log;

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
                "number" => "104",
                "postalCode" => "68000",
                "type" => "origin",
                "company" => "Raices Artesanas",
                "name" => "Raices Artesanas",
                "email" => "cardozob76121@gmail.com",
                "phone" => "9514537503",
                "country" => "MX",
                "street" => "Humboldt",
                "district" => "Centro",
                "city" => "Oaxaca",
                "state" => "OAX",
                "phone_code" => "MX",
                "reference" => "local de artesanías"
            ],
            "destination" => [
                "number" => "2470",
                "postalCode" => "64060",
                "type" => "destination",
                "company" => "Tendencys",
                "name" => "Diego Fernando Hernandez",
                "email" => "test@envia.com",
                "phone" => "8180808080",
                "country" => "MX",
                "street" => "Belisario Dominguez",
                "district" => "Obispado",
                "city" => "Monterrey",
                "state" => "NL",
                "phone_code" => "MX",
                "reference" => "389"
            ],
            "packages" => [
                [
                    "content" => "camisetas rojas",
                    "amount" => 1,
                    "type" => "box",
                    "dimensions" => [
                        "length" => 46,
                        "width" => 30,
                        "height" => 20
                    ],
                    "weight" => 5,
                    "insurance" => 400,
                    "declaredValue" => 400,
                    "weightUnit" => "KG",
                    "lengthUnit" => "CM"
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

     

      try {
    $response = $this->enviaService->createShipment($data);

    if ($response) {
        $this->info('Shipping label generated successfully!');
        $this->comment("------------------------------------");

        if (!empty($response['data'][0])) {
            $shipment = $response['data'][0];
            $this->comment("🆔 Shipment ID: " . ($shipment['shipmentId'] ?? 'N/A'));
            $this->comment("📦 Tracking Number: " . ($shipment['trackingNumber'] ?? 'N/A'));
            $this->comment("🔗 Tracking URL: " . ($shipment['trackUrl'] ?? 'N/A'));
            $this->comment("🖨️ Label PDF URL: " . ($shipment['additionalFiles']['pdf'] ?? 'N/A'));
            $this->comment("💲 Price Total: " . ($shipment['totalPrice'] ?? 'N/A'));
            $this->comment("💰 Balance: " . ($shipment['currentBalance'] ?? 'N/A'));
        } else {
            $this->warn('La respuesta no contiene datos de envío en el índice [0].');
            $this->line('Respuesta completa: ' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $this->comment("------------------------------------");
        return Command::SUCCESS;
    } else {
        $this->error('Failed to generate shipping label with Envia.com.');
        return Command::FAILURE;
    }
} catch (\Exception $e) {
    $this->error('Error inesperado al generar la guía: ' . $e->getMessage());
    Log::error('Excepción:', ['exception' => $e]);
    return Command::FAILURE;
}
    }
}
