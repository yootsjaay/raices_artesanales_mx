<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Artesania;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Services\EnviaService;
use App\Models\State;
use App\Models\TipoEmbalaje;
use Illuminate\Support\Str;
use App\Services\MercadoPagoServices;
use Illuminate\Support\Facades\Session; // Importamos Session

class CheckoutController extends Controller
{
    protected $enviaService;
    protected $mercadoPagoService;

    public function __construct(EnviaService $enviaService, MercadoPagoServices $mercadoPagoService)
    {
        $this->enviaService = $enviaService;
        $this->mercadoPagoService = $mercadoPagoService;
    }

    /**
     * Muestra el formulario para ingresar o seleccionar una dirección de envío.
     */
    public function showShippingForm()
    {
        $user = Auth::user();
        $states = State::orderBy('name')->get();
        // ... Lógica para cargar direcciones y carrito ...
        $cartItems = Cart::where('user_id', $user->id)->with('cart_items')->first()->cart_items ?? collect();
        $addresses = $user->addresses; // Asumiendo relación en el modelo User

        return view('checkout.shipping', compact('states', 'cartItems', 'addresses'));
    }


    /**
     * Procesa la dirección de envío seleccionada y realiza la cotización.
     */
    public function processShippingAndQuote(Request $request)
    {
        // 1. Validar la dirección de envío
        $request->validate([
            'carrier' => 'required|in:fedex,dhl,ups,paquetexpress',
        ]);

        $user = Auth::user();
        $carrierOption = $request->input('carrier');
        
        // 2. Obtener la dirección de envío
        $shippingAddressId = session('checkout.shipping_address_id');
        if (!$shippingAddressId || !($shippingAddress = Address::find($shippingAddressId))) {
            return redirect()->route('checkout.shipping')
                             ->with('error', 'Por favor, selecciona una dirección de envío primero.');
        }
        
        // 3. Cargar el carrito y verificar su estado
        $cart = $user->cart()->with(['cart_items.artesania', 'cart_items.artesania_variant'])->first();
        if (!$cart || $cart->cart_items->isEmpty()) {
            // 💡 CORRECCIÓN 1: Redirección al nombre de ruta correcto 'carrito.index'
            return redirect()->route('carrito.mostrar')
                             ->with('error', 'Tu carrito está vacío, no puedes proceder al checkout.');
        }

        // 4. Calcular el peso y el valor total
        $totalWeight = 0;
        $totalDeclaredValue = 0;
        $totalQuantity = 0;
        foreach ($cart->cart_items as $cartItem) {
            $product = $cartItem->artesania_variant ?? $cartItem->artesania;
            // Usamos un solo campo de peso para consistencia
            $productWeight = ($product->peso_item_kg ?? 0); 
            
            $totalWeight += $productWeight * $cartItem->quantity;
            $totalDeclaredValue += $cartItem->price * $cartItem->quantity;
            $totalQuantity += $cartItem->quantity;
        }

        if ($totalWeight <= 0) {
            // 💡 CORRECCIÓN 1: Redirección al nombre de ruta correcto 'carrito.index'
            return redirect()->route('carrito.index')
                             ->with('error', 'No se pudo calcular el peso de tus productos. Por favor, asegúrate de que tengan un peso asignado.');
        }

        // 5. Encontrar una sola caja que pueda contener el peso total (Lógica de Empaquetado)
        $packages = [];
        $selectedBox = TipoEmbalaje::where('is_active', 1)
                                   // 💡 CAMBIO LOGÍSTICO: Buscamos una caja que soporte el peso total
                                   ->where('weight', '>=', $totalWeight)
                                   ->orderBy('weight') // La caja más pequeña que soporta el peso
                                   ->first();
        
        // 💡 CAMBIO LOGÍSTICO: Eliminamos el fallback peligroso. Si no hay caja, fallamos.
        if (!$selectedBox) {
            Log::error('El pedido excede la capacidad de embalaje.', ['peso' => $totalWeight]);
            // 💡 CORRECCIÓN 1: Redirección al nombre de ruta correcto 'carrito.index'
            return redirect()->route('carrito.index')
                             ->with('error', 'Lo sentimos, el peso total de tu pedido excede nuestra capacidad de embalaje para envío. Por favor, contacta a soporte.');
        }

        // Agregamos la caja a los paquetes que cotizaremos
        $packages[] = [
            'content' => 'Artesanías varias',
            'weight' => $totalWeight,
            'weight_unit' => 'KG',
            'length' => $selectedBox->length,
            'width' => $selectedBox->width,
            'height' => $selectedBox->height,
            'length_unit' => 'CM',
            'type' => 1, // Tipo de paquete (Caja)
            'declared_value' => round($totalDeclaredValue, 2),
            'quantity' => 1,
        ];


        // 6. Preparar Payload para la cotización
        $originAddress = [
            'country' => 'MX',
            'postal_code' => env('ENVIA_ORIGIN_POSTAL_CODE', '68000'), // Código postal de origen desde .env
        ];

        $destinationAddress = [
            'country' => 'MX',
            'postal_code' => $shippingAddress->postal_code,
            // 💡 BUENA PRÁCTICA: Limpiamos caracteres que rompen APIs de envío
            'state' => Str::ascii(strtoupper($shippingAddress->state)), 
        ];

        $payload = [
            'origin' => $originAddress,
            'destination' => $destinationAddress,
            'packages' => $packages,
            'carrier' => $carrierOption,
        ];

        // 7. Cotizar con el servicio
        $quotes = $this->enviaService->quote($payload);

        if (empty($quotes)) {
            Log::warning('No se encontraron cotizaciones para el envío.', ['payload' => $payload]);
            return back()->with('error', 'No se encontraron cotizaciones de envío para tu dirección. Intenta con otra paquetería.');
        }

        // 8. Guardar cotizaciones y payload en la sesión y redirigir
        Session::put('checkout.shipping_quotes', $quotes);
        // 💡 CORRECCIÓN 3: Corregido el typo 'heckout' a 'checkout'
        Session::put('checkout.shipping_payload', $payload); 

        return redirect()->route('checkout.shipping_method');
    }

    /**
     * Muestra el formulario para que el usuario seleccione el método de envío.
     * 💡 CORRECCIÓN 4: Método implementado para solucionar el error 'undefined method'
     */
   public function showShippingMethodForm()
{
    // 1. Recuperar las cotizaciones de envío de la sesión
    $shippingQuotes = Session::get('checkout.shipping_quotes');
    
    // 2. Si no hay cotizaciones, redirigir al paso anterior (cotización)
    if (empty($shippingQuotes)) {
        return redirect()->route('checkout.shipping')
                         ->with('error', 'Por favor, cotiza tu envío primero para ver las opciones.');
    }

    // 3. Recuperar la dirección de envío (se necesita para mostrar el resumen)
    $shippingAddressId = Session::get('checkout.shipping_address_id');
    
    // Si la dirección no está en la sesión o no existe en DB, redirigir al paso 1
    if (!$shippingAddressId || !($shippingAddress = Address::find($shippingAddressId))) {
        return redirect()->route('checkout.shipping')
                         ->with('error', 'La dirección de envío seleccionada no es válida. Por favor, revísala.');
    }

    // 4. Mostrar la vista con las cotizaciones y la dirección
    return view('checkout.shipping-method', [
        'quotes' => $shippingQuotes,
        'shippingAddress' => $shippingAddress,
    ]);
}

    /**
     * Procesa el método de envío seleccionado y prepara el pago (Mercado Pago).
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'shipping_rate' => 'required|numeric',
        ]);
        
        // Recuperar datos de la sesión
        $shippingRate = $request->input('shipping_rate');
        $shippingPayload = Session::get('checkout.shipping_payload');
        
        // 💡 CORRECCIÓN 5: Obtener el ID de la sesión y buscar la Address correctamente
        $shippingAddressId = Session::get('checkout.shipping_address_id');
        $shippingAddress = Address::find($shippingAddressId);
        
        $user = Auth::user();
        $cart = $user->cart()->with('cart_items')->first();
        
        // ... (Verificaciones de seguridad y lógica de creación de orden) ...
        
        // Simplemente como ejemplo, obtendremos el total del carrito para el pago
        $cartTotal = $cart->cart_items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $totalPagar = $cartTotal + $shippingRate;

        // Lógica de Mercado Pago
        // ...
        $preference = $this->mercadoPagoService->createPreference($user, $cart, $shippingRate, $shippingPayload);

        if ($preference) {
            // Limpiar la sesión de checkout
            Session::forget(['checkout.shipping_address_id', 'checkout.shipping_quotes', 'checkout.shipping_payload']);
            
            // Redirigir a Mercado Pago
            return redirect()->away($preference->init_point);
        }

        return back()->with('error', 'No se pudo crear la preferencia de pago. Intenta más tarde.');
    }

    // ... (paymentSuccess, paymentPending, paymentFailure, confirmation, handleMercadoPagoWebhook) ...
    
    // (Resto de métodos del controlador sin cambios)

    public function paymentSuccess(Request $request){
        // ... (lógica de éxito) ...
    }

    public function paymentPending(Request $request){
        // ... (lógica de pendiente) ...
    }

    public function paymentFailure(Request $request){
        // ... (lógica de fallo) ...
    }

    public function confirmation(Order $order){
        // ... (lógica de confirmación) ...
    }

    public function handleMercadoPagoWebhook(Request $request)
    {
        // ... (lógica del webhook) ...
    }
}