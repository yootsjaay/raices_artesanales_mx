<?php

namespace App\Services;

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient; // <--- Importar PaymentClient
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;

class MercadoPagoServices implements MercadoPagoInterface
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
        // Opcional: Para pruebas en local. Asegúrate de quitarlo en producción.
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    public function crearPreferenciaDePago(array $items, array $backUrls, string $notificationUrl, string $externalReference, array $payerData, array $metadata = []): object
    {
        $client = new PreferenceClient();
        // ... (Tu implementación existente para crear preferencias de Checkout Pro) ...
        try {
            $preferenceRequest = [
                "items" => $items,
                "payer" => [
                    "email" => $payerData['email'] ?? null,
                    "name" => $payerData['name'] ?? null,
                    "surname" => $payerData['surname'] ?? null,
                ],
                "back_urls" => $backUrls,
                "notification_url" => $notificationUrl,
                "external_reference" => $externalReference,
                "auto_return" => "approved",
                "metadata" => $metadata,
            ];

            $request_options = new RequestOptions();
            $request_options->setCustomHeaders([
                "X-Idempotency-Key: " . uniqid("pref_", true)
            ]);

            $preference = $client->create($preferenceRequest, $request_options);

            return $preference;

        } catch (MPApiException $e) {
            Log::error("Error MercadoPago API al crear preferencia: " . $e->getMessage() . " Detalles: " . json_encode($e->getApiResponse()));
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error general MercadoPago al crear preferencia: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Procesa un pago directo con token de tarjeta.
     * Implementación del nuevo método.
     */
    public function procesarPagoDirecto(float $amount, string $token, array $payerData, string $description, int $installments, string $externalReference): object
    {
        $paymentClient = new PaymentClient(); // <--- Usamos PaymentClient para pagos directos

        try {
            $paymentRequest = [
                "transaction_amount" => $amount,
                "token" => $token,
                "description" => $description,
                "installments" => $installments,
                // "payment_method_id" => "visa", // No es necesario si el token es válido, MP lo deduce
                "payer" => [
                    "email" => $payerData['email'] ?? null,
                    "first_name" => $payerData['name'] ?? null,
                    "last_name" => $payerData['surname'] ?? null,
                    "identification" => [
                        "type" => $payerData['identification_type'] ?? null,
                        "number" => $payerData['identification_number'] ?? null,
                    ],
                ],
                // Opcional: URL de notificación para webhooks de este pago directo
                "notification_url" => route('mercadopago.webhook'), // Puedes usar el mismo webhook si tu handleWebhook es robusto
                "external_reference" => $externalReference,
                // Puedes añadir un statement_descriptor
                // "statement_descriptor" => "RAICES ARTESANALES",
            ];

            $request_options = new RequestOptions();
            $request_options->setCustomHeaders(["X-Idempotency-Key: " . uniqid("direct_pay_", true)]);

            $payment = $paymentClient->create($paymentRequest, $request_options);

            return $payment;

        } catch (MPApiException $e) {
            Log::error("Error MercadoPago API al procesar pago directo: " . $e->getMessage() . " Detalles: " . json_encode($e->getApiResponse()));
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error general al procesar pago directo: " . $e->getMessage());
            throw $e;
        }
    }

    public function handleWebhook(array $data): bool
    {
        // ... (Tu implementación existente del handleWebhook) ...
        Log::info('Webhook de Mercado Pago recibido en el servicio: ', $data);

        $topic = $data['topic'] ?? null;
        $id = $data['id'] ?? null;

        if (!$topic || !$id) {
            Log::warning('Webhook recibido sin topic o ID.');
            return false;
        }

        try {
            if ($topic === 'payment') {
                $paymentClient = new \MercadoPago\Client\Payment\PaymentClient();
                $payment = $paymentClient->get($id);

                if ($payment && isset($payment->external_reference)) {
                    $order = \App\Models\Order::where('external_reference', $payment->external_reference)->first();

                    if ($order) {
                        Log::info("Webhook de pago para orden #{$order->id}. Estado MP: {$payment->status}");

                        if ($payment->status === 'approved') {
                            $order->status = 'completed';
                            // Asegúrate de que el stock se reduce solo una vez, si no lo hiciste al crear la orden.
                            // Si el stock se reduce al crear la orden, verifica que no se duplique aquí.
                        } elseif ($payment->status === 'pending') {
                            $order->status = 'pending_payment';
                        } elseif ($payment->status === 'rejected') {
                            $order->status = 'failed';
                            // Revertir el stock si fue reducido al crear la orden.
                        }
                        $order->mp_payment_id = $payment->id;
                        $order->save();
                        return true;
                    } else {
                        Log::warning('Webhook de pago: Orden no encontrada con external_reference: ' . $payment->external_reference);
                    }
                }
            }
            // Puedes añadir lógica para 'merchant_order' u otros topics si es necesario.
            // Para la compra directa, solo 'payment' suele ser relevante.

        } catch (MPApiException $e) {
            Log::error("Error MercadoPago API al procesar webhook de pago: " . $e->getMessage() . " Detalles: " . json_encode($e->getApiResponse()));
            return false;
        } catch (\Exception $e) {
            Log::error("Error general al procesar webhook: " . $e->getMessage());
            return false;
        }

        return false;
    }
}