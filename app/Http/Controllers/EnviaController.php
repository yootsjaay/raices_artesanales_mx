<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EnviaService;
use Illuminate\Validation\ValidationException;

class EnviaController extends Controller
{
    protected $enviaService;

    // Definir los carriers que quieres probar
    protected $carriers = ['fedex', 'dhl', 'estafeta', 'redpack'];

    public function __construct(EnviaService $enviaService)
    {
        $this->enviaService = $enviaService;
    }

    public function index()
    {
        // Datos por default para el formulario
        $defaultData = [
            'origin_postal' => '66236',
            'origin_state' => 'NL',
            'origin_city' => 'Monterrey',
            'origin_street' => 'av vasconcelos',
            'origin_number' => '1400',
            'destination_postal' => '66240',
            'destination_state' => 'NL',
            'destination_city' => 'Monterrey',
            'destination_street' => 'av vasconcelos',
            'destination_number' => '1400',
            'packages' => [
                [
                    'weight' => 63,
                    'height' => 5,
                    'width' => 5,
                    'length' => 2,
                    'declaredValue' => 400,
                ]
            ],
        ];

        return view('envia.index', compact('defaultData'));
    }

    public function quote(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->route('envia.index');
        }

        $quotes = [];

        try {
            // Validación básica, ojo que valida los paquetes también
            $validatedData = $request->validate([
                'origin_postal' => 'required|string|max:10',
                'origin_state' => 'nullable|string|max:10',
                'origin_city' => 'nullable|string|max:255',
                'origin_street' => 'nullable|string|max:255',
                'origin_number' => 'nullable|string|max:20',

                'destination_postal' => 'required|string|max:10',
                'destination_state' => 'nullable|string|max:10',
                'destination_city' => 'nullable|string|max:255',
                'destination_street' => 'nullable|string|max:255',
                'destination_number' => 'nullable|string|max:20',

                'packages' => 'required|array|min:1',
                'packages.*.weight' => 'required|numeric|min:0.1',
                'packages.*.height' => 'required|numeric|min:0.1',
                'packages.*.width' => 'required|numeric|min:0.1',
                'packages.*.length' => 'required|numeric|min:0.1',
                'packages.*.declaredValue' => 'nullable|numeric|min:0',
            ]);
            foreach ($quotes as $carrier => $data) {
                $quotes[$carrier]['data'] = array_filter($data['data'], function ($option) {
                    return isset($option['price']) && $option['price'] > 0;
                });
            }


            return view('envia.index', [
                'quotes' => $quotes,
                'defaultData' => $validatedData,
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error inesperado en EnviaController:', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error inesperado.');
        }
    }

    protected function buildPayload(array $data, string $carrier, Request $request): array
    {
        return [
            'origin' => [
                'number' => $data['origin_number'] ?? '',
                'postalCode' => $data['origin_postal'],
                'type' => 'origin',
                'company' => $request->input('origin_company', 'Default Company'),
                'name' => $request->input('origin_name', 'Nombre Remitente'),
                'email' => $request->input('origin_email', 'correo@example.com'),
                'phone' => $request->input('origin_phone', '0000000000'),
                'country' => 'MX',
                'street' => $data['origin_street'] ?? '',
                'district' => $request->input('origin_district', ''),
                'city' => $data['origin_city'] ?? '',
                'state' => $data['origin_state'] ?? '',
                'phone_code' => 'MX',
                'address_id' => $request->input('origin_address_id', null),
                'category' => 1,
                'identificationNumber' => '389',
                'reference' => '',
                'coordinates' => [
                    'latitude' => '19.027686',
                    'longitude' => '72.853462',
                ],
            ],
            'destination' => [
                'number' => $data['destination_number'] ?? '',
                'postalCode' => $data['destination_postal'],
                'type' => 'destination',
                'company' => $request->input('destination_company', 'Destinatario S.A.'),
                'name' => $request->input('destination_name', 'Nombre Destinatario'),
                'email' => $request->input('destination_email', 'correo@example.com'),
                'phone' => $request->input('destination_phone', '0000000000'),
                'country' => 'MX',
                'street' => $data['destination_street'] ?? '',
                'district' => $request->input('destination_district', ''),
                'city' => $data['destination_city'] ?? '',
                'state' => $data['destination_state'] ?? '',
                'phone_code' => 'MX',
                'address_id' => $request->input('destination_address_id', null),
                'category' => 1,
                'identificationNumber' => '389',
                'reference' => '389',
                'coordinates' => [
                    'latitude' => '19.027686',
                    'longitude' => '72.853462',
                ],
            ],
            'packages' => collect($data['packages'])->map(function ($pkg) {
                return [
                    'type' => $pkg['type'] ?? 'envelope',
                    'content' => $pkg['content'] ?? 'Contenido',
                    'amount' => $pkg['amount'] ?? 1,
                    'name' => $pkg['name'] ?? 'Paquete',
                    'declaredValue' => $pkg['declaredValue'] ?? 0,
                    'lengthUnit' => 'CM',
                    'weightUnit' => 'KG',
                    'weight' => (float)$pkg['weight'],
                    'dimensions' => [
                        'length' => (float)$pkg['length'],
                        'width' => (float)$pkg['width'],
                        'height' => (float)$pkg['height'],
                    ],
                    'additionalServices' => [
                        [
                            'data' => ['amount' => 2000],
                            'service' => 'cash_on_delivery',
                        ],
                        [
                            'data' => ['amount' => 2000],
                            'service' => 'insurance',
                        ],
                    ],
                ];
            })->toArray(),
            'settings' => [
                'printFormat' => 'PDF',
                'printSize' => 'STOCK_4X6',
                'currency' => 'MXN',
                'comments' => '',
            ],
            'shipment' => [
                'type' => 1,
                'import' => 0,
                'carrier' => $carrier,
            ]
        ];
    }
}
