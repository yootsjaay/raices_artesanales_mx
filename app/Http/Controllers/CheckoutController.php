<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CarritoController;
use App\Services\MercadoPagoInterface; // <--- Importa tu interfaz

class CheckoutController extends Controller
{
    protected $carritoController;
    protected $mpService;

   public function __construct(CarritoController $carritoController, MercadoPagoInterface $mpService) // <--- Aquí está el problema
{
    $this->carritoController = $carritoController;
    $this->mpService = $mpService;
}

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para proceder al pago.');
        }

        $cart = $this->carritoController->getOrCreateCart();

        if (!$cart) {
            return redirect()->route('carrito.mostrar')->with('error', 'No se pudo obtener tu carrito. Intenta de nuevo.');
        }

        $cartItems = $cart->items()->with('artesania')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        if ($cartItems->isEmpty()) {
            return redirect()->route('carrito.mostrar')->with('error', 'Tu carrito está vacío. No puedes proceder al pago.');
        }

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para completar el pago.');
        }

        $cart = $this->carritoController->getOrCreateCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('carrito.mostrar')->with('error', 'Tu carrito está vacío o no se encontró para procesar el pago.');
        }

        $itemsForMp = [];
        $totalAmount = 0;

        foreach ($cart->items as $item) {
            $unitPrice = (float) $item->price;
            $itemsForMp[] = [
                "title" => $item->artesania->nombre,
                "quantity" => $item->quantity,
                "unit_price" => $unitPrice,
            ];
            $totalAmount += ($item->quantity * $unitPrice);
        }

        try {
            $backUrls = [
                "success" => route('checkout.success'),
                "failure" => route('checkout.failure'),
                "pending" => route('checkout.pending'),
            ];

            $notificationUrl = route('mercadopago.webhook');
            $externalReference = 'ORDER_' . Auth::id() . '_' . time();
            $payerData = ['email' => Auth::user()->email]; // Datos del pagador
            $metadata = [
                "cart_id" => $cart->id,
                "user_id" => Auth::id(),
            ];

            // <--- Llama al nuevo método del servicio para crear la preferencia
            $preference = $this->mpService->crearPreferenciaDePago($itemsForMp, $backUrls, $notificationUrl, $externalReference, $payerData, $metadata);

            // 1. Guardar la orden en tu base de datos con estado "pendiente"
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $totalAmount,
                'status' => 'pending',
                'mp_preference_id' => $preference->id ?? null,
                'external_reference' => $externalReference,
            ]);

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'artesania_id' => $cartItem->artesania_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
                // Reducir el stock de la artesanía
                $artesania = $cartItem->artesania;
                if ($artesania) {
                    $artesania->stock -= $cartItem->quantity;
                    $artesania->save();
                }
            }

            // 2. Vaciar el carrito del usuario
            $cart->items()->delete();
            $cart->delete();

            // 3. Redirigir al usuario a la URL de pago de Mercado Pago
            if (isset($preference->init_point)) {
                return redirect()->away($preference->init_point);
            } else {
                Log::error('Mercado Pago service did not return an init_point.');
                return redirect()->route('checkout.inicio')->with('error', 'Error al obtener la URL de pago de Mercado Pago.');
            }

        } catch (\Exception $e) {
            Log::error('Error al procesar pago con Mercado Pago: ' . $e->getMessage());
            return redirect()->route('checkout.inicio')->with('error', 'Hubo un error al iniciar el pago: ' . $e->getMessage());
        }
    }

    // Método para manejar el webhook de Mercado Pago (lo llamará Mercado Pago)
    public function handleWebhook(Request $request)
    {
        // Delegar la lógica del webhook a tu servicio
        $processed = $this->mpService->handleWebhook($request->all());

        if ($processed) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Webhook not processed'], 400);
        }
    }

    // ... (Métodos success, failure, pending existentes) ...
}