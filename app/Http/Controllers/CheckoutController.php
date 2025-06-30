<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address; // ¡Asegúrate de que este sea tu modelo de dirección!
use App\Models\Artesania; // ¡Importa tu modelo Artesania!
use App\Models\CartItem; // ¡Importa tu modelo CartItem!
use App\Models\User; // Asegúrate de importar User si lo usas directamente
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Services\EnviaService;

class CheckoutController extends Controller
{
    protected $enviaService;

   public function __construct(EnviaService $enviaService)
    {
       // parent::__construct();
        $this->enviaService = $enviaService;
    }

    /**
     * Muestra el formulario para ingresar una nueva dirección de envío.
     */
    public function showShippingForm()
    {
        $user = Auth::user();
        // Carga la relación 'cart_items' en lugar de 'items'
        // y dentro de 'cart_items', carga 'artesania' en lugar de 'product'
        $cart = $user->cart()->with('cart_items.artesania')->first();

        if (!$cart || $cart->cart_items->isEmpty()) { // Usamos 'cart_items' aquí también
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío. No puedes proceder al checkout.');
        }

        return view('checkout.shipping', compact('cart'));
    }

    /**
     * Procesa la dirección de envío enviada y la guarda como un nuevo registro.
     */
    public function processShipping(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'company' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'internal_number' => 'nullable|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone_code' => 'nullable|string|max:255',
            'category' => 'nullable|integer',
            'identification_number' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        $shippingAddress = $user->addresses()->create([
            'company' => $request->company,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'street' => $request->street,
            'number' => $request->number,
            'internal_number' => $request->internal_number,
            'district' => $request->district,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone_code' => $request->phone_code ?? 'MX',
            'category' => $request->category ?? 1,
            'identification_number' => $request->identification_number,
            'reference' => $request->reference,
            'type_address' => 'shipping',
            'is_default' => false,
        ]);

        session(['checkout.shipping_address_id' => $shippingAddress->id]);

        return redirect()->route('checkout.shipping_method');
    }

    /**
     * Muestra las opciones de envío obtenidas de Envia.com.
     */
   public function showShippingMethodForm()
{
    $user = Auth::user();
    $cart = $user->cart()->with('cart_items.artesania')->first();

    if (!$cart || $cart->cart_items->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío, no puedes proceder al checkout.');
    }

    $shippingAddressId = session('checkout.shipping_address_id');
    if (!$shippingAddressId) {
        return redirect()->route('checkout.shipping')->with('error', 'Por favor, selecciona una dirección de envío primero.');
    }
    $shippingAddress = Address::find($shippingAddressId);

    // Aquí mandamos la info completa del origen, NO solo el ID
    $originAddress = [
        "number" => "104",
        "postalCode" => "68000",
        "type" => "origin",
        "company" => "Raices Artesanas",
        "name" => "Bralio Cardozo Vasquez",
        "email" => "raices@artesanales.mx",
        "phone" => "9514537503",
        "country" => "MX",
        "street" => "Humboldt",
        "district" => "Colonia Centro",
        "city" => "Oaxaca",
        "state" => "OAX",  // usa abreviatura de estado
        "phone_code" => "MX",
        "category" => 1,
        "identificationNumber" => "N/A",
        "reference" => "Local de artesanias "
    ];

    $destinationAddress = [
        "company" => $shippingAddress->company,
        "name" => $shippingAddress->name,
        "email" => $shippingAddress->email ?? $user->email,
        "phone" => $shippingAddress->phone,
        "street" => $shippingAddress->street,
        "number" => $shippingAddress->number,
        "internal_number" => $shippingAddress->internal_number,
        "district" => $shippingAddress->district,
        "city" => $shippingAddress->city,
        "state" => $shippingAddress->state,
        "postalCode" => $shippingAddress->postal_code,
        "country" => $shippingAddress->country,
        "phone_code" => $shippingAddress->phone_code,
        "category" => $shippingAddress->category,
        "identificationNumber" => $shippingAddress->identification_number,
        "reference" => $shippingAddress->reference,
        "type" => "destination",
    ];

    $packages = [];
    foreach ($cart->cart_items as $cartItem) {
        $artesania = $cartItem->artesania;

        if (!$artesania || !$artesania->weight || !$artesania->length || !$artesania->width || !$artesania->height) {
            Log::error('Artesanía sin datos completos para cotización', [
                'cart_item_id' => $cartItem->id,
                'artesania_id' => $artesania->id ?? 'N/A',
            ]);
            return redirect()->back()->with('error', 'Algunas artesanías en tu carrito no tienen información completa para el envío.');
        }

        $packages[] = [
            "type" => "box",
            "content" => $artesania->name ?? "Producto",
            "amount" => $cartItem->quantity,
            "name" => $artesania->name ?? "Producto",
            "declaredValue" => $cartItem->quantity * $cartItem->price,
            "lengthUnit" => "CM",
            "weightUnit" => "KG",
            "weight" => $artesania->weight * $cartItem->quantity,
            "dimensions" => [
                "length" => $artesania->length,
                "width" => $artesania->width,
                "height" => $artesania->height,
            ],
            "additionalServices" => []
        ];
    }

    if (empty($packages)) {
        Log::warning('No se generaron paquetes para la cotización', ['cart_id' => $cart->id]);
        session()->flash('error', 'No hay productos válidos para cotizar el envío.');
        return redirect()->route('cart.index');
    }

    $payload = [
        "origin" => $originAddress,
        "destination" => $destinationAddress,
        "packages" => $packages,
        "settings" => [
            "printFormat" => "PDF",
            "printSize" => "STOCK_4X6",
            "currency" => "MXN",
            "comments" => "Cotización para pedido de artesanías"
        ],
        "shipment" => [
            "type" => 1,
            "import" => 0,
            "carrier" => "dhl",
        ]
    ];

    Log::info('Payload para Envia.com desde CheckoutController:', $payload);
    $quotes = $this->enviaService->quoteShipping($payload);
    Log::info('Respuesta RAW de Envia.com para cotización:', ['quotes' => $quotes]);

    if ($quotes && isset($quotes['data']) && is_array($quotes['data']) && !empty($quotes['data'])) {
        $shippingOptions = $quotes['data'];
        Log::info('Opciones de envío obtenidas', ['count' => count($shippingOptions)]);
    } else {
        Log::warning('No se obtuvieron opciones de envío válidas.', [
            'quotes_raw' => $quotes,
            'payload_sent' => $payload
        ]);
        session()->flash('error', 'No se pudieron obtener opciones de envío. Verifica la dirección e intenta de nuevo.');
        return redirect()->route('checkout.shipping');
    }

    usort($shippingOptions, fn($a, $b) => ($a['totalPrice'] ?? PHP_INT_MAX) <=> ($b['totalPrice'] ?? PHP_INT_MAX));

    return view('checkout.shipping_method', compact('cart', 'shippingAddress', 'shippingOptions'));
}

    /**
     * Procesa la selección del método de envío y guarda en sesión.
     */
    public function processShippingMethod(Request $request)
    {
        $request->validate([
            'shipping_option' => 'required|json',
        ]);

        $selectedShippingOption = json_decode($request->input('shipping_option'), true);

        session([
            'checkout.shipping_method_description' => $selectedShippingOption['serviceDescription'],
            'checkout.shipping_cost' => $selectedShippingOption['totalPrice'],
            'checkout.carrier_name' => $selectedShippingOption['carrier'],
            'checkout.carrier_service_id' => $selectedShippingOption['serviceId'],
            'checkout.delivery_estimate' => $selectedShippingOption['deliveryEstimate'],
        ]);

        return redirect()->route('checkout.payment');
    }

    /**
     * Muestra el formulario de pago y el resumen del pedido.
     */
    public function showPaymentForm()
    {
        $user = Auth::user();
        // Carga la relación 'cart_items' y dentro de ella 'artesania'
        $cart = $user->cart()->with('cart_items.artesania')->first();

        if (!$cart || $cart->cart_items->isEmpty()) { // Usamos 'cart_items' aquí
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $shippingAddressId = session('checkout.shipping_address_id');
        $shippingMethodDetails = session('checkout.shipping_method_description');
        $shippingCost = session('checkout.shipping_cost', 0);
        $carrierName = session('checkout.carrier_name');
        $deliveryEstimate = session('checkout.delivery_estimate');


        if (!$shippingAddressId || !$shippingMethodDetails || $shippingCost === null) {
            return redirect()->route('checkout.shipping')->with('error', 'Por favor, completa los pasos anteriores del checkout.');
        }
        $shippingAddress = Address::find($shippingAddressId); // Usamos el modelo Address

        // Sumamos los precios de las artesanías en el carrito
        $subtotal = $cart->cart_items->sum(fn($item) => $item->quantity * $item->price);
        $totalAmount = $subtotal + $shippingCost;

        return view('checkout.payment', compact(
            'cart',
            'shippingAddress',
            'shippingMethodDetails',
            'shippingCost',
            'subtotal',
            'totalAmount',
            'carrierName',
            'deliveryEstimate'
        ));
    }

    /**
     * Procesa el pago y crea el pedido.
     */
    public function processPayment(Request $request)
    {
        $user = Auth::user();
        // Carga la relación 'cart_items' y dentro de ella 'artesania'
        $cart = $user->cart()->with('cart_items.artesania')->first();

        if (!$cart || $cart->cart_items->isEmpty()) { // Usamos 'cart_items' aquí
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $shippingAddressId = session('checkout.shipping_address_id');
        $shippingMethodDescription = session('checkout.shipping_method_description');
        $shippingCost = session('checkout.shipping_cost');
        $carrierName = session('checkout.carrier_name');
        $carrierServiceId = session('checkout.carrier_service_id');
        $deliveryEstimate = session('checkout.delivery_estimate');


        if (!$shippingAddressId || !$shippingMethodDescription || $shippingCost === null || !$carrierName || !$carrierServiceId) {
            return redirect()->route('checkout.shipping')->with('error', 'Información de envío incompleta. Por favor, selecciona un método de envío.');
        }
        $shippingAddress = Address::find($shippingAddressId); // Usamos el modelo Address

        $subtotal = $cart->cart_items->sum(fn($item) => $item->quantity * $item->price);
        $totalAmount = $subtotal + $shippingCost;

        // --- LÓGICA DE INTEGRACIÓN CON PASARELA DE PAGO ---
        try {
            $paymentStatus = 'paid';
            $transactionId = 'TXN_'. uniqid() . '_' . time();

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }

        // --- CREACIÓN DEL PEDIDO Y ENVÍO DENTRO DE UNA TRANSACCIÓN DE BD ---
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'tax_amount' => 0,
                'shipping_method' => $shippingMethodDescription,
                'payment_method' => 'card',
                'payment_status' => $paymentStatus,
                'transaction_id' => $transactionId,
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $shippingAddress->id,
                'carrier_name' => $carrierName,
                'carrier_service_id' => $carrierServiceId,
                'delivery_estimate' => $deliveryEstimate,
            ]);

            foreach ($cart->cart_items as $cartItem) { // ¡Cambiado a 'cart_items'!
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->artesania_id, // Usamos 'artesania_id'
                    'product_name' => $cartItem->artesania->name ?? 'Artesanía Eliminada', // Usamos 'artesania'
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                if ($cartItem->artesania) { // Usamos 'artesania'
                    $cartItem->artesania->decrement('stock', $cartItem->quantity);
                }
            }

            // --- CONSTRUIR EL PAYLOAD PARA CREAR EL ENVÍO (SHIPMENT) EN ENVIA.COM ---
            $originAddressPayload = [
                "id" => config('envia.origin_address_id')
            ];

            $destinationAddressPayload = [
                "company" => $shippingAddress->company,
                "name" => $shippingAddress->name,
                "email" => $shippingAddress->email ?? $user->email,
                "phone" => $shippingAddress->phone,
                "street" => $shippingAddress->street,
                "number" => $shippingAddress->number,
                "internal_number" => $shippingAddress->internal_number,
                "district" => $shippingAddress->district,
                "city" => $shippingAddress->city,
                "state" => $shippingAddress->state,
                "postalCode" => $shippingAddress->postal_code,
                "country" => $shippingAddress->country,
                "phone_code" => $shippingAddress->phone_code,
                "category" => $shippingAddress->category,
                "identificationNumber" => $shippingAddress->identification_number,
                "reference" => $shippingAddress->reference,
                "type" => "destination",
            ];

            $packagesPayload = [];
            foreach ($cart->cart_items as $cartItem) { // ¡Cambiado a 'cart_items'!
                $artesania = $cartItem->artesania; // ¡Cambiado a 'artesania'!
                $packagesPayload[] = [
                    "type" => "package",
                    "content" => $artesania->name,
                    "amount" => $cartItem->quantity,
                    "name" => $artesania->name,
                    "declaredValue" => $cartItem->quantity * $cartItem->price,
                    "lengthUnit" => "CM",
                    "weightUnit" => "KG",
                    "weight" => $artesania->weight * $cartItem->quantity,
                    "dimensions" => [
                        "length" => $artesania->length,
                        "width" => $artesania->width,
                        "height" => $artesania->height,
                    ],
                    "additionalServices" => []
                ];
            }

            $shipmentPayload = [
                "origin" => $originAddressPayload,
                "destination" => $destinationAddressPayload,
                "packages" => $packagesPayload,
                "settings" => [
                    "printFormat" => "PDF",
                    "printSize" => "STOCK_4X6",
                    "currency" => "MXN",
                    "comments" => "Pedido #" . $order->id,
                ],
                "shipment" => [
                    "type" => 1,
                    "import" => 0,
                    "carrier" => $carrierName,
                    "service" => $carrierServiceId,
                ]
            ];

            $enviaShipment = $this->enviaService->createShipment($shipmentPayload);

            if ($enviaShipment && isset($enviaShipment['data']['id']) && isset($enviaShipment['data']['label'])) {
                $order->envia_shipment_id = $enviaShipment['data']['id'];
                $order->tracking_number = $enviaShipment['data']['trackingNumber'] ?? null;
                $order->label_url = $enviaShipment['data']['label'] ?? null;
                $order->status = 'processing';
                $order->save();
            } else {
                Log::error("Fallo al crear la guía de envío en Envia.com para el pedido #{$order->id}.", ['response' => $enviaShipment]);
                throw new \Exception('Error al generar la guía de envío. Por favor, contacta a soporte.');
            }

            $cart->status = 'ordered';
            $cart->save();
            $cart->cart_items()->delete(); // ¡Cambiado a 'cart_items'!

            DB::commit();

            session()->forget([
                'checkout.shipping_address_id',
                'checkout.shipping_method_description',
                'checkout.shipping_cost',
                'checkout.carrier_name',
                'checkout.carrier_service_id',
                'checkout.delivery_estimate'
            ]);

            return redirect()->route('order.confirmation', $order)->with('success', '¡Tu pedido ha sido realizado con éxito y la guía de envío generada!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al procesar tu pedido: ' . $e->getMessage());
        }
    }
}