<?php

namespace App\Http\Controllers;

use App\Services\EnviaService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShippingController extends Controller
{
    protected $enviaService;

    public function __construct(EnviaService $enviaService)
    {
        $this->enviaService = $enviaService;
    }
    public function showQuoteForm()
{
    $defaultData = [
        'origin' => [
            'postalCode' => '66236',
            'city' => 'San Pedro Garza García',
            'state' => 'NL',
            'street' => 'Vasconcelos',
            'name' => 'Tu Empresa',
            'phone' => '8180808080',
            'email' => 'contacto@tuempresa.com'
        ],
        'destination' => [
            'postalCode' => '64060',
            'city' => 'Monterrey',
            'state' => 'NL',
            'street' => 'Belisario Dominguez',
            'name' => 'Cliente',
            'phone' => '8180808080',
            'email' => 'cliente@ejemplo.com'
        ],
        'packages' => [
            [
                'weight' => 0.5,
                'dimensions' => [
                    'length' => 20,
                    'width' => 15,
                    'height' => 10
                ],
                'content' => 'Productos varios',
                'declaredValue' => 500
            ]
        ],
        'shipment' => [
            'carrier' => 'fedex',
            'type' => 1
        ]
    ];

    return view('envia.index', compact('defaultData'));
}
    /**
     * Cotiza un envío usando la API de Envia.com
     */
    public function getQuote(Request $request)
    {
        // Valida los datos mínimos requeridos (ajusta según tu caso)
        $validated = $request->validate([
            'origin.postalCode' => 'required|string',
            'destination.postalCode' => 'required|string',
            'packages.*.weight' => 'required|numeric',
        ]);

        // Estructura base del payload (basado en tu ejemplo)
        $payload = [
            'origin' => [
                'postalCode' => $request->input('origin.postalCode'),
                'country' => 'MX',
                'type' => 'origin',
                // Completa con más campos o usa valores por defecto
                'name' => $request->input('origin.name', 'Default Origin Name'),
                'street' => $request->input('origin.street', 'Default Street'),
                'city' => $request->input('origin.city', 'Default City'),
                'state' => $request->input('origin.state', 'Default State'),
                'phone' => $request->input('origin.phone', '0000000000'),
                'email' => $request->input('origin.email', 'default@example.com'),
            ],
            'destination' => [
                'postalCode' => $request->input('destination.postalCode'),
                'country' => 'MX',
                'type' => 'destination',
                // Completa igual que origin
                'name' => $request->input('destination.name', 'Default Destination Name'),
                'street' => $request->input('destination.street', 'Default Street'),
                'city' => $request->input('destination.city', 'Default City'),
                'state' => $request->input('destination.state', 'Default State'),
                'phone' => $request->input('destination.phone', '0000000000'),
                'email' => $request->input('destination.email', 'default@example.com'),
            ],
            'packages' => [
                [
                    'type' => 'envelope',
                    'weight' => $request->input('packages.0.weight', 0.5),
                    'dimensions' => [
                        'length' => $request->input('packages.0.length', 20),
                        'width' => $request->input('packages.0.width', 15),
                        'height' => $request->input('packages.0.height', 10),
                    ],
                    'weightUnit' => 'KG',
                    'lengthUnit' => 'CM',
                ]
            ],
            'shipment' => [
                'type' => 1, // 1 = Estándar (ver documentación)
                'carrier' => $request->input('carrier', 'quiken'), // Carrier por defecto
            ],
            'settings' => [
                'printFormat' => 'PDF',
                'currency' => 'MXN',
            ]
        ];

        try {
            $quote = $this->enviaService->getQuote($payload);
            
            if (!$quote) {
                return response()->json([
                    'error' => 'No se pudo obtener la cotización'
                ], 500);
            }

            return response()->json($quote);

        } catch (ValidationException $e) {
            // Captura errores de validación de la API
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Otros errores
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}