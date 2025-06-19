<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Para manejar datos en la sesión
use App\Services\EnviaService;
class CheckoutController extends Controller
{
    protected $enviaService;

public function __construct(EnviaService $enviaService){
    $this->enviaService = $enviaService;
}

public function checkout(Request $request)
{
    $cart = Cart::with('items.artesania')
                ->where('user_id', Auth::id())
                ->firstOrFail();

    $validated = $request->validate([
        'name' => 'required|string',
        'company' => 'nullable|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'street' => 'required|string',
        'number' => 'required|string',
        'district' => 'nullable|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'postal_code' => 'required|string',
        'reference' => 'nullable|string',
    ]);

    $dimensions = $this->calculateTotalPackage($cart->items);

    $envioPayload = [
        "origin" => config('envia.origin'),
        "destination" => array_merge($validated, [
            "type" => "destination",
            "country" => "MX",
            "phone_code" => "MX"
        ]),
        "packages" => [[
            "content" => "Compra de artesanías",
            "amount" => 1,
            "type" => "box",
            "dimensions" => [
                'length' => $dimensions['length'],
                'width'  => $dimensions['width'],
                'height' => $dimensions['height'],
            ],
            "weight" => $dimensions['weight'],
            "declaredValue" => $this->calculateCartValue($cart),
            "weightUnit" => "KG",
            "lengthUnit" => "CM",
        ]],
        "shipment" => [
            "carrier" => "fedex",
            "service" => "ground",
            "type" => 1,
            "currency" => "MXN",
        ],
        "settings" => [
            "printFormat" => "PDF",
            "printSize" => "STOCK_4X6",
            "comments" => "Envío desde Raíces Artesanales"
        ]
    ];

    $enviaResponse = $this->enviaService->createShipment($envioPayload);

    $shipmentData = $enviaResponse['data'][0] ?? [];

    $shipment = Shipment::create([
        'cart_id' => $cart->id,
        'destination_name' => $validated['name'],
        'destination_company' => $validated['company'],
        'destination_email' => $validated['email'],
        'destination_phone' => $validated['phone'],
        'destination_street' => $validated['street'],
        'destination_number' => $validated['number'],
        'destination_district' => $validated['district'],
        'destination_city' => $validated['city'],
        'destination_state' => $validated['state'],
        'destination_postal_code' => $validated['postal_code'],
        'destination_country' => 'MX',
        'destination_reference' => $validated['reference'],
        'carrier' => 'fedex',
        'service' => 'ground',
        'order_id' => $shipmentData['shipmentId'] ?? null,
        'tracking_number' => $shipmentData['trackingNumber'] ?? null,
        'label_url' => $shipmentData['additionalFiles']['pdf'] ?? null,
        'status' => 'generado',
        'total_weight' => $dimensions['weight'],
        'length' => $dimensions['length'],
        'width' => $dimensions['width'],
        'height' => $dimensions['height'],
    ]);

    return redirect()->route('checkout.success')->with('shipment_id', $shipment->id);
}

public function show()
{
    $cart = Cart::with('items.artesania')
                ->where('user_id', Auth::id())
                ->firstOrFail();

    return view('checkout', compact('cart'));
}

}    


