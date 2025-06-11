<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook MercadoPago recibido', $request->all());
        
        // Validar firma si está en producción
        if (config('services.mercadopago.env') === 'prod') {
            // Implementar validación de firma
        }

        $data = $request->json()->all();
        $type = $data['type'] ?? '';
        $payment = $data['data']['id'] ?? null;

        if ($type === 'payment' && $payment) {
            $this->processPayment($payment);
        }

        return response()->json(['status' => 'success']);
    }

    protected function processPayment($paymentId)
    {
        try {
            $payment = $this->mercadoPago->getPayment($paymentId);
            
            // Obtener metadatos del pago
            $metadata = $payment->metadata ?? null;
            $cartId = $metadata->cart_id ?? null;
            $userId = $metadata->user_id ?? null;
            
            if (!$cartId || !$userId) {
                Log::error("Metadata faltante en pago: $paymentId");
                return;
            }
            
            // Buscar carrito
            $cart = Cart::with('items.artesania')->find($cartId);
            
            if (!$cart) {
                Log::error("Carrito no encontrado: $cartId");
                return;
            }
            
            // Crear orden
            $order = Order::create([
                'user_id' => $userId,
                'payment_id' => $paymentId,
                'status' => $payment->status,
                'total' => $payment->transaction_amount,
                'payment_method' => $payment->payment_method_id,
            ]);
            
            // Crear items de la orden
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'artesania_id' => $item->artesania_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
                
                // Actualizar stock
                $item->artesania->decrement('stock', $item->quantity);
            }
            
            // Limpiar carrito
            $cart->items()->delete();
            
            Log::info("Orden creada: #{$order->id} para pago: $paymentId");

        } catch (\Exception $e) {
            Log::error("Error procesando webhook: " . $e->getMessage());
        }
    }
 //
}
