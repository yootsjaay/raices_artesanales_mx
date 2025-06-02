<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EnviaService;
// use App\Http\Requests\QuoteShippingRequest; // Si creaste un Form Request para validar el destino

class EnviaController extends Controller
{
    protected $enviaService;

    public function __construct(EnviaService $enviaService)
    {
        $this->enviaService = $enviaService;
    }

    public function showQuoteForm()
    {
        return view('envia.quote_form');
    }

    public function postQuote(Request $request) // O QuoteShippingRequest $request
    {
        // --- 1. Validar los datos de DESTINO ingresados por el usuario ---
        $validatedDestinationData = $request->validate([
            'destination.name' => 'required|string',
            'destination.email' => 'required|email',
            'destination.phone' => 'required|string',
            'destination.street' => 'required|string',
            'destination.number' => 'required|string',
            'destination.district' => 'required|string',
            'destination.zip' => 'required|string',
            'destination.city' => 'required|string',
            'destination.state' => 'required|string',
        ]);

        // --- 2. Preparar los datos de ORIGEN (de tu tienda, no del usuario) ---
        $originData = [
            "number" => "1400",
            "postalCode" => "66236",
            "type" => "origin",
            "company" => "Mi Tienda Ejemplo",
            "name" => "Tu Nombre",
            "email" => "tu_email@tutienda.com",
            "phone" => "8180161135",
            "country" => "MX",
            "street" => "Calle de tu tienda",
            "district" => "Tu Colonia",
            "city" => "Tu Ciudad",
            "state" => "Tu Estado (ej. NL)",
            "phone_code" => "MX",
        ];

        // --- 3. Preparar los datos de los PAQUETES (de tus productos, no del usuario) ---
        $packagesData = [
            [
                "type" => "box",
                "content" => "Ropa y Accesorios",
                "amount" => 1,
                "name" => "Paquete de Prueba",
                "declaredValue" => 100.00,
                "lengthUnit" => "CM",
                "weightUnit" => "KG",
                "weight" => 0.5,
                "dimensions" => [
                    "length" => 20,
                    "width" => 15,
                    "height" => 5
                ],
            ]
        ];

        // --- 4. Ensamblar el payload COMPLETO para la API de Envia ---
        $payload = [
            "origin" => $originData,
            "destination" => array_merge($validatedDestinationData['destination'], [
                "type" => "destination", // Campos fijos para el destino
                "country" => "MX",
                "phone_code" => "MX",
            ]),
            "packages" => $packagesData,
            "settings" => [
                "currency" => "MXN"
            ],
            // No incluyas 'shipment' si quieres todas las opciones de cotización
        ];

        // --- 5. Llamar al servicio de Envia para obtener las cotizaciones ---
        $shippingOptions = $this->enviaService->getShippingQuotes($payload);

        // --- 6. Manejar la respuesta y mostrarla en la vista ---
        if ($shippingOptions) {
            return view('envia.quote_results', compact('shippingOptions'));
        } else {
            return back()->with('error', 'No se pudieron obtener las opciones de envío. Por favor, intente de nuevo.');
        }
    }

}