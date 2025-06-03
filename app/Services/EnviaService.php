<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EnviaService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.envia.api_token'); // Usa .env
        $this->baseUrl = 'https://api-test.envia.com/ship/rate'; // Ajusta si usas otro endpoint
    }
 public function cotizar(array $data)
    {
        $payload = [
    "origin" => [
        "postal_code" => $data['origin_postal'],
        "country" => "MX",
        "state" => "NL",
        "city" => "San Pedro Garza García",
        "street" => "Vasconcelos",
        "number" => "1400"
    ],
    "destination" => [
        "postal_code" => $data['destination_postal'],
        "country" => "MX",
        "state" => "NL",
        "city" => "Monterrey",
        "street" => "Belisario Dominguez",
        "number" => "2470"
    ],
    "parcels" => [[ // aquí también cambias a 'parcels'
        "weight" => (float) $data['weight'],
        "height" => (int) $data['height'],
        "width" => (int) $data['width'],
        "length" => (int) $data['length'],
        "declared_value" => (float) $data['declaredValue'] ?? 0
    ]],
    "declared_value" => (float) $data['declaredValue'] ?? 0,
    "shipment" => [
        "type" => 1,
        "import" => 0
    ]
];


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api-test.envia.com/ship/rate', $payload);

        if ($response->successful()) {
    return $response->json();
} else {
    dd('Error:', $response->status(), $response->json());
}

    }
}
