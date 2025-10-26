<?php

namespace App\Services;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient; // ✨ NECESARIO para consultar pagos en el webhook
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException; // Para ser explícitos
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;         // ✨ NECESARIO para handleWebhook
use Illuminate\Support\Facades\DB;   // ✨ NECESARIO para la transacción
use App\Models\Order;                // ✨ NECESARIO (Ajusta el namespace de tu modelo)
use App\Jobs\ProcessMercadoPagoPayment; // ✨ OPCIONAL/RECOMENDADO: Para el procesamiento asíncrono
use Exception;

class MercadoPagoServices { 

    protected $preferenceClient; // ✍️ Renombrado para claridad
    protected $paymentClient;    // ✨ NECESARIO: Cliente para consultar pagos

    public function __construct()
    {
        // 🔑 Configuración del Access Token
        MercadoPagoConfig::setAccessToken(config('mercadoservice.mercadopago.access_token'));
        
        // ✨ Inicialización de ambos clientes
        $this->preferenceClient = new PreferenceClient(); 
        $this->paymentClient = new PaymentClient();      
    }

    // -------------------------------------------------------------
    // MÉTODO PARA CREAR LA PREFERENCIA DE PAGO
    // -------------------------------------------------------------
    public function createPreference($cart, $shippingAddress, $selectedQuote, $orderId)
    {
        try {
            // 📝 Mapear los items del carrito para el formato de Mercado Pago
            $items = $cart->cart_items->map(function ($item) {
                // Asunción: Los modelos tienen las relaciones correctas
                $product = $item->artesania_variant ?? $item->artesania; 
                return [
                    // Se incluye el ID, el título, descripción, cantidad y precio.
                    "id" => (string) $product->id, // Recomendado asegurar que es string
                    "title" => $product->name,
                    "description" => $product->description,
                    "quantity" => (int) $item->quantity, // Asegurar tipo
                    "unit_price" => (float) $item->price, // Asegurar tipo
                ];
            })->toArray();
            
            // 📦 Agregar el costo de envío como un item separado
            $shippingCost = $selectedQuote['totalPrice'] ?? 0;
            $items[] = [
                "title" => "Costo de Envío",
                "description" => "Envío con {$selectedQuote['carrier']['name']} - {$selectedQuote['service']['name']}",
                "quantity" => 1,
                "unit_price" => (float) $shippingCost,
            ];

            // 👤 Preparar la información del comprador (payer)
            $user = $cart->user;
            $payer = [
                "name" => $user->name,
                "surname" => $user->last_name,
                "email" => $user->email,
                // 📞 Añadir el teléfono mejora la tasa de conversión
                "phone" => [
                    "area_code" => $shippingAddress->area_code ?? "DEFAULT", 
                    "number" => $shippingAddress->phone_number,
                ],
                "address" => [
                    "zip_code" => $shippingAddress->postal_code,
                    "street_name" => $shippingAddress->street,
                    "street_number" => $shippingAddress->number,
                ],
            ];

            // ⚙️ Crear la preferencia con datos dinámicos
            $preference = $this->preferenceClient->create([ // Usar preferenceClient
                "items" => $items,
                "payer" => $payer,
                "back_urls" => [
                    // Usar helper 'route' de Laravel
                    "success" => route('checkout.success'),
                    "pending" => route('checkout.pending'),
                    "failure" => route('checkout.failure'),
                ],
                "auto_return" => "approved",
                "external_reference" => (string) $orderId, // Clave para el webhook
                "notification_url" => route('mercadopago.webhook'), // URL pública del webhook
            ]);

            return $preference;

        } catch (MPApiException $e) { // Excepción de la API de MP (Error de negocio)
            $errorContent = $e->getApiResponse()->getContent();
            Log::error('Error al crear preferencia de Mercado Pago: ' . json_encode($errorContent));
            throw new Exception("Error al procesar el pago. Por favor, inténtalo de nuevo. Detalles: " . $errorContent['message'] ?? 'Error desconocido');

        } catch (Exception $e) { // Excepción general (Error de código/conexión)
            Log::error('Error inesperado en MercadoPagoService: ' . $e->getMessage());
            throw new Exception("Error interno al procesar el pago. Por favor, contacta a soporte.");
        }
    }

    // -------------------------------------------------------------
    // MÉTODO PARA MANEJAR EL WEBHOOK DE NOTIFICACIONES
    // -------------------------------------------------------------
    public function handleWebhook(Request $request)
    {
        $data = $request->all();
        
        // 1. Validar el tipo de evento y recurso
        if (($data['type'] ?? null) !== 'payment') {
            Log::info('Webhook ignorado (no es pago):', $data);
            return; // Devuelve 200 OK para evitar reintentos de MP
        }

        $resourceId = $data['data']['id'] ?? null;
        if (is_null($resourceId)) {
            Log::error('Webhook recibido sin ID de recurso:', $data);
            return; 
        }

        try {
            // 2. Consultar el pago a la API de Mercado Pago para confirmar la validez
            $payment = $this->paymentClient->get($resourceId);

            // 3. Obtener el ID de la orden
            $orderId = $payment->external_reference;
            $order = Order::find($orderId);

            if (!$order) {
                Log::error("Orden no encontrada con external_reference: {$orderId} para el pago {$resourceId}");
                return;
            }

            $newPaymentStatus = $payment->status;

            // 4. Procesar solo si el estado es nuevo (evita reintentos o redundancia)
            if ($order->payment_status !== $newPaymentStatus) {
                
                // ⚡️ RECOMENDACIÓN: Mover la lógica pesada a un Job Asíncrono
                // Esto asegura que la respuesta HTTP 200 al webhook sea inmediata, 
                // evitando reintentos de Mercado Pago.

                DB::transaction(function () use ($order, $newPaymentStatus, $resourceId) {
                    $order->update([
                        'payment_id' => $resourceId, 
                        'payment_status' => $newPaymentStatus,
                    ]);

                    if ($newPaymentStatus === 'approved') {
                        // El pago fue aprobado. 
                        // Mueve la lógica compleja (stock, carrito, email) a un Job o Evento.
                        $order->update(['status' => 'processing']);
                        // event(new PaymentApproved($order)); 
                        
                    } elseif ($newPaymentStatus === 'rejected') {
                        $order->update(['status' => 'cancelled']);
                        // Si se había reservado stock, aquí se liberaría.
                    }
                    
                    Log::info("Orden {$order->id} actualizada a estado de pago: {$newPaymentStatus} (Pago MP: {$resourceId})");
                });

                // Si usas un Job (Mejor Práctica):
                // ProcessMercadoPagoPayment::dispatch($order->id, $resourceId, $newPaymentStatus);

            } else {
                Log::info("Estado de pago para la orden {$orderId} ya es {$newPaymentStatus}. Procesamiento omitido.");
            }

        } catch (MPApiException $e) {
            Log::error('Error de API al consultar pago en Webhook: ' . json_encode($e->getApiResponse()->getContent()), ['resourceId' => $resourceId]);
        } catch (Exception $e) {
            Log::error('Error general al procesar Webhook:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }
}