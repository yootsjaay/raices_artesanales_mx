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
public function checkoutForm()
{
    $cart = Cart::with('items.artesania')->where('user_id', auth()->id())->firstOrFail();
    $total = $cart->items->sum(fn($item) => $item->subtotal);

    return view('checkout.form', compact('cart', 'total'));
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

    $dimensions = $this->enviaService->quoteShipping($cart->$items);

    $destination = [
    "number"     => $validated['number'],
    "postalCode" => $validated['postal_code'],
    "type"       => "destination",
    "company"    => $validated['company'] ?? '', // opcional
    "name"       => $validated['name'],
    "email"      => $validated['email'],
    "phone"      => $validated['phone'],
    "country"    => "MX",
    "street"     => $validated['street'],
    "district"   => $validated['district'] ?? '',
    "city"       => $validated['city'],
    "state"      => $validated['state'],
    "phone_code" => "MX",
    "reference"  => $validated['reference'] ?? '',
];

$envioPayload = [
    "origin" => config('envia.origin'), // asegúrate de que esté bien configurado en config/envia.php

    "destination" => $destination,

    "packages" => [[
        "content"       => "Compra de artesanías",
        "amount"        => 1,
        "type"          => "box",
        "dimensions"    => [
            "length" => $dimensions['length'],
            "width"  => $dimensions['width'],
            "height" => $dimensions['height'],
        ],
        "weight"        => $dimensions['weight'],
        "insurance"     => $this->calculateCartValue($cart), // opcional si quieres asegurar
        "declaredValue" => $this->calculateCartValue($cart),
        "weightUnit"    => "KG",
        "lengthUnit"    => "CM",
    ]],

    "shipment" => [
        "carrier"  => "fedex",  // puedes hacerlo dinámico después
        "service"  => "ground",
        "type"     => 1,
        "currency" => "MXN",
    ],

    "settings" => [
        "printFormat" => "PDF",
        "printSize"   => "STOCK_4X6",
        "comments"    => "Envío desde Raíces Artesanales",
    ],
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

    return view('checkout.shipping', compact('cart'));
}

}    


