<?php

namespace App\Console\Commands;

use App\Services\EnviaService;
use App\Models\TipoEmbalaje;
use Illuminate\Console\Command;
use Exception;

class TestEnviaQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'envia:test-quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene cotizaciones de Envia.com según el tipo de paquete y carrier.';

    /**
     * The EnviaService instance.
     *
     * @var EnviaService
     */
    protected EnviaService $enviaService;

    // Constantes para los datos de dirección, facilitan la reusabilidad y el mantenimiento.
    private const ORIGIN_ADDRESS = [
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
    ];

    private const DESTINATION_ADDRESS = [
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
        "state" => "NLE",
        "phone_code" => "MX",
        "category" => 1,
        "identificationNumber" => "389",
        "reference" => "389"
    ];

    /**
     * Create a new command instance.
     *
     * @param EnviaService $enviaService
     * @return void
     */
    public function __construct(EnviaService $enviaService)
    {
        parent::__construct();
        $this->enviaService = $enviaService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Intentando obtener cotizaciones de envío de Envia.com...');

        // Selección de carrier
        $carrierOption = $this->choice(
            'Selecciona la paquetería a cotizar',
            ['dhl', 'fedex', 'ups' , 'paquetexpress'] ,
            0
        );

        $this->info("Vas a cotizar: {$carrierOption}");

        // Obtener embalajes activos
        $embalajes = TipoEmbalaje::where('is_active', 1)->get();

        foreach ($embalajes as $embalaje) {
            // Definir package.type según la lógica del tamaño/uso
            $packageType = match($embalaje->nombre) {
                'Sobre pequeño' => 'envelope',
                'Caja chica', 'Caja mediana', 'Caja grande', 'Caja extra' => 'box',
                default => 'box',
            };

            $this->info("Cotizando embalaje: {$embalaje->nombre} con tipo: {$packageType}");

            // Se define un valor declarado de 2000, que se usará para el seguro y el cobro a la entrega.
            $declaredValue = 2000;
            $descripcion = $embalaje->descripcion ?? $embalaje->nombre;

            $data = [
                "origin" => self::ORIGIN_ADDRESS,
                "destination" => self::DESTINATION_ADDRESS,
                "packages" => [
                    [
                        "type" => $packageType,
                        "package" => $embalaje->package_envia_id,
                        "content" => $descripcion,
                        "amount" => 1,
                        "declaredValue" => $declaredValue,
                        "weight" => $embalaje->weight,
                        "dimensions" => [
                            "length" => $embalaje->length,
                            "width" => $embalaje->width,
                            "height" => $embalaje->height
                        ],
                        // Se utilizan las variables para los servicios adicionales, evitando valores duplicados.
                        "additionalServices" => [
                            ["data" => ["amount" => $declaredValue], "service" => "cash_on_delivery"],
                            ["data" => ["amount" => $declaredValue], "service" => "insurance"]
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
                    "carrier" => $carrierOption,
                ]
            ];

            try {
                $quote = $this->enviaService->quoteShipping($data);

                if ($quote && isset($quote['data']) && is_array($quote['data'])) {
                    $this->info("Cotizaciones recibidas para {$carrierOption}:");
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
                    $this->warn("No se recibieron cotizaciones para {$embalaje->nombre}.");
                    $this->line('Respuesta: ' . json_encode($quote, JSON_PRETTY_PRINT));
                }
            } catch (Exception $e) {
                $this->error("Error al cotizar embalaje {$embalaje->nombre}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
