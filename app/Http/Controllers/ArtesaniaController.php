<?php

namespace App\Http\Controllers;

use App\Models\Artesania; // Importa tu modelo Artesania
use Illuminate\Http\Request;
use App\Services\MercadoPagoInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class ArtesaniaController extends Controller
{
  

 
    public function index()
    {
        // Obtiene todas las artesanías de la base de datos
        // CORREGIDO: Hemos quitado 'artesano' de la carga eagerly (with())
        // ya que la relación y el modelo Artesano ya no existen.
        $artesanias = Artesania::with(['categoria', 'ubicacion'])->get();

        // Pasa las artesanías a la vista 'artesanias.index'
        return view('artesanias.index', compact('artesanias'));
    }

    /**
     * Muestra los detalles de una artesanía específica.
     */
    public function show(Artesania $artesania) // Inyección de modelo: Laravel encuentra la artesanía por el ID en la URL
    {
       
   
        // Esto es crucial para que los comentarios aparezcan en la vista
        $artesania->load(['comments' => function ($query) {
            $query->where('status', 'approved')->with('user')->latest();
        }]);

        return view('artesanias.show', compact('artesania'));
    
    }
     public function directBuy(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Debes iniciar sesión para comprar directamente.'], 401);
        }

        $request->validate([
            'artesania_id' => 'required|exists:artesanias,id',
            'cantidad' => 'required|integer|min:1',
            'card_token' => 'required|string',
            'card_holder_name' => 'required|string',
            'doc_type' => 'required|string',
            'doc_number' => 'required|string',
        ]);

        $artesania = Artesania::findOrFail($request->artesania_id);
        $quantity = $request->cantidad;
        $totalAmount = $artesania->precio * $quantity;

        if ($artesania->stock < $quantity) {
            return response()->json(['success' => false, 'message' => 'Lo sentimos, solo hay ' . $artesania->stock . ' unidades disponibles de esta artesanía.'], 400);
        }

        try {
            $externalReference = 'DIRECT_BUY_' . Auth::id() . '_' . time();

            $payerData = [
                'email' => Auth::user()->email,
                'name' => Auth::user()->name,
                // 'surname' => Auth::user()->last_name, // Si tienes last_name
                'identification_type' => $request->doc_type,
                'identification_number' => $request->doc_number,
            ];

            // Llama al servicio para procesar el pago directo
            $payment = $this->mpService->procesarPagoDirecto(
                $totalAmount,
                $request->card_token,
                $payerData,
                "Compra de " . $artesania->nombre,
                1, // Cuotas fijas en 1 para este ejemplo
                $externalReference
            );

            // Manejar la respuesta del pago
            if ($payment->status === 'approved') {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total' => $totalAmount,
                    'status' => 'completed',
                    'mp_payment_id' => $payment->id,
                    'external_reference' => $externalReference,
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'artesania_id' => $artesania->id,
                    'quantity' => $quantity,
                    'price' => $artesania->precio,
                ]);

                $artesania->stock -= $quantity;
                $artesania->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Pago aprobado. Su compra ha sido registrada.',
                    'redirect_url' => route('checkout.success', ['external_reference' => $externalReference, 'status' => $payment->status]),
                ]);

            } elseif ($payment->status === 'pending') {
                 $order = Order::create([
                    'user_id' => Auth::id(),
                    'total' => $totalAmount,
                    'status' => 'pending_payment',
                    'mp_payment_id' => $payment->id,
                    'external_reference' => $externalReference,
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'artesania_id' => $artesania->id,
                    'quantity' => $quantity,
                    'price' => $artesania->precio,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pago pendiente de confirmación. Te enviaremos un correo cuando se apruebe.',
                    'redirect_url' => route('checkout.pending', ['external_reference' => $externalReference, 'status' => $payment->status]),
                ]);

            } else {
                Log::error('Pago directo no aprobado/pendiente: ' . json_encode($payment));
                return response()->json([
                    'success' => false,
                    'message' => 'El pago fue ' . ($payment->status_detail ?? 'rechazado') . '. Por favor, intenta con otra tarjeta o método de pago.',
                    'redirect_url' => route('checkout.failure', ['external_reference' => $externalReference, 'status' => $payment->status]),
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Error al procesar compra directa a través del servicio: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error interno del servidor. Intenta de nuevo.'], 500);
        }
    }
}