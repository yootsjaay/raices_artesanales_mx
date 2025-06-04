<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EnviaService;
use Illuminate\Validation\ValidationException;

class EnviaController extends Controller
{
    protected $enviaService;

    public function __construct(EnviaService $enviaService)
    {
        $this->enviaService = $enviaService;
    }

    public function index()
    {
        $quotes = null;

        $defaultData = [
            'origin_postal' => '66236',
            'origin_state' => 'NL',
            'origin_city' => 'Monterrey',
            'origin_street' => 'av vasconcelos',
            'origin_number' => '1400',

            'destination_postal' => '66240',
            'destination_state' => 'NL',
            'destination_city' => 'monterrey',
            'destination_street' => 'av vasconcelos',
            'destination_number' => '1400',

            'weight' => 63,
            'height' => 5,
            'width' => 5,
            'length' => 2,
            'declaredValue' => 400,
        ];

        return view('envia.index', compact('quotes', 'defaultData'));
    }

    public function quote(Request $request)
    {
        $quotes = null;

        if ($request->isMethod('post')) {
            try {
                // Validación
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
                    'packages.*.width'  => 'required|numeric|min:0.1',
                    'packages.*.length' => 'required|numeric|min:0.1',
                    'packages.*.declaredValue' => 'nullable|numeric|min:0',
                ]);

              $payload = [
    'origin' => [
        'name' => $request->input('origin_name', 'default name'),
        'company' => $request->input('origin_company', 'default company'),
        'email' => $request->input('origin_email', 'default@example.com'),
        'phone' => $request->input('origin_phone', '0000000000'),
        'street' => $validatedData['origin_street'] ?? '',
        'number' => $validatedData['origin_number'] ?? '',
        'district' => $request->input('origin_district', ''),
        'city' => $validatedData['origin_city'] ?? '',
        'state' => $validatedData['origin_state'] ?? '',
        'country' => 'MX',
        'postalCode' => $validatedData['origin_postal'],
        'reference' => $request->input('origin_reference', ''),
    ],
    'destination' => [
        'name' => $request->input('destination_name', 'default name'),
        'company' => $request->input('destination_company', 'default company'),
        'email' => $request->input('destination_email', 'default@example.com'),
        'phone' => $request->input('destination_phone', '0000000000'),
        'street' => $validatedData['destination_street'] ?? '',
        'number' => $validatedData['destination_number'] ?? '',
        'district' => $request->input('destination_district', ''),
        'city' => $validatedData['destination_city'] ?? '',
        'state' => $validatedData['destination_state'] ?? '',
        'country' => 'MX',
        'postalCode' => $validatedData['destination_postal'],
        'reference' => $request->input('destination_reference', ''),
    ],
    'packages' => collect($validatedData['packages'])->map(function ($pkg) {
        return [
            'content' => $pkg['content'] ?? 'sin descripción',
            'amount' => $pkg['amount'] ?? 1,
            'type' => $pkg['type'] ?? 'box',
            'dimensions' => [
                'length' => (float) $pkg['length'],
                'width' => (float) $pkg['width'],
                'height' => (float) $pkg['height'],
            ],
            'weight' => (float) $pkg['weight'],
            'insurance' => $pkg['insurance'] ?? 0,
            'declaredValue' => $pkg['declaredValue'] ?? 0,
            'weightUnit' => 'KG',
            'lengthUnit' => 'CM',
        ];
    })->toArray(),
    'shipment' => [
        'carrier' => $request->input('shipment_carrier', 'fedex'),
        'service' => $request->input('shipment_service', 'express'),
        'type' => (int) $request->input('shipment_type', 1),
    ],
    'settings' => [
        'printFormat' => $request->input('settings_printFormat', 'PDF'),
        'printSize' => $request->input('settings_printSize', 'STOCK_4X6'),
        'comments' => $request->input('settings_comments', ''),
    ],
];

                // Llamada al servicio
                $quotes = $this->enviaService->getQuote($payload);

                if (is_null($quotes)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'No se pudo obtener la cotización. Revise los datos y los logs.');
                }

                // Retorna la vista con quotes y con los datos para mantener el formulario lleno
                return view('envia.index', [
                    'quotes' => $quotes,
                    'defaultData' => $validatedData
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

        // Si no es POST, simplemente redirige a index
        return redirect()->route('envia.index');
    }
}
