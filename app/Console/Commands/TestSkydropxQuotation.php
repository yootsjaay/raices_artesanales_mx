<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\SkydropxService;

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

            $this->info("Introspectando token...");
            $introspection = $skydropx->introspectToken($token);
            $this->line(json_encode($introspection, JSON_PRETTY_PRINT));

            if (!($introspection['active'] ?? false)) {
                $this->error('Token no activo o inválido, no se puede continuar.');
                return 1;
            }

            $this->info("Generando Orden de prueba...");

            $orderData = [
                'reference' => 'RAICES-001',
                'payment_status' => 'paid',
                'total_price' => '1000.00',
                'merchant_store_id' => 'store123',
                'headquarter_id' => 'hq001',
                'platform' => 'raices Artesanias Oaxaquenas',
                'package_type' => 'box',
                'parcels' => [
                    [
                        'weight' => 2.5,
                        'length' => 30,
                        'width' => 20,
                        'height' => 15,
                        'quantity' => 1,
                        'dimension_unit' => 'cm',
                        'mass_unit' => 'kg',
                        'package_type' => 'box',
                    ],
                ],
                'shipper_address' => [
                    'address' => 'Calle Humboldt',
                    'internal_number' => '104',
                    'reference' => 'A unas cuadras de santo domingo',
                    'sector' => 'Centro',
                    'city' => 'Ciudad Oaxaca',
                    'state' => 'Oaxaca',
                    'postal_code' => '68000',
                    'country' => 'MX',
                    'person_name' => 'Diego Fer',
                    'company' => 'Raices Artesanias Oaxaquena',
                    'phone' => '9514537503',
                    'email' => 'uk.aquino@example.com',
                ],
                'recipient_address' => [
                    'address' => 'Avenida Siempre Viva 742',
                    'internal_number' => '',
                    'reference' => '',
                    'sector' => 'La Colonia',
                    'city' => 'Oaxaca de Juárez',
                    'state' => 'Oaxaca',
                    'postal_code' => '68020',
                    'country' => 'MX',
                    'person_name' => 'Bart Simpson',
                    'company' => 'Springfield Nuclear',
                    'phone' => '0987654321',
                    'email' => 'bart@simpson.com',
                ],
            ];

            $order = $skydropx->createOrder($orderData);
           $orderId = $order['data']['id'] ?? $order['data']['attributes']['id'] ?? null;

            if (!$orderId) {
                $this->error("No se pudo obtener el ID de la orden para cotizar.");
                return 1;
            }

            $this->info("Orden creada con ID: {$orderId}");

            $this->info("Orden creada exitosamente:");
            $this->line(json_encode($order, JSON_PRETTY_PRINT));

            // 🧠 Preparar datos para la cotización
            $this->info("Realizando cotización de prueba...");


            $quotationData = [
    "quotation" => [
        'order_id' => $orderId,
        "address_from" => [
            "country_code" => "MX",
            "postal_code" => "68000",
            "area_level1" => "Oaxaca",
            "area_level2" => "Ciudad Mixe",
            "area_level3" => "Centro",
        ],
        "address_to" => [
            "country_code" => "MX",
            "postal_code" => "68020",
            "area_level1" => "Oaxaca",
            "area_level2" => "Oaxaca de Juárez",
            "area_level3" => "La Colonia",
        ],
        "parcel" => [
            "length" => 30,
            "width" => 20,
            "height" => 15,
            "weight" => 2.5,
        ],
        "requested_carriers" => ["estafeta", "dhl", "fedex"] // opcional, pero útil
    ]
];

$quote = $skydropx->cotizar($quotationData);

$this->info("Cotización generada:");
$this->line(json_encode($quote, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->error("Error al probar SkydropX: " . $e->getMessage());
            Log::error("SkydropX Test Error", ['exception' => $e]);
        }
    }
}
