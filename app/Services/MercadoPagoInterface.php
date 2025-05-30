<?php

namespace App\Services;

interface MercadoPagoInterface
{
    /**
     * Crea una preferencia de pago en Mercado Pago para uno o varios ítems (Checkout Pro).
     * @param array $items Array de ítems, cada uno con 'title', 'quantity', 'unit_price'.
     * @param array $backUrls Array con 'success', 'failure', 'pending' URLs.
     * @param string $notificationUrl URL para webhooks de notificación.
     * @param string $externalReference Una referencia única para tu orden.
     * @param array $payerData Datos del pagador (ej. 'email').
     * @param array $metadata Datos adicionales para pasar a MP.
     * @return object Un objeto de Preferencia de Mercado Pago (que contiene el init_point).
     */
    public function crearPreferenciaDePago(array $items, array $backUrls, string $notificationUrl, string $externalReference, array $payerData, array $metadata = []): object;

    /**
     * Procesa un pago directo con token de tarjeta.
     * @param float $amount Monto total a pagar.
     * @param string $token Token de la tarjeta generado en el frontend.
     * @param array $payerData Datos del pagador (email, identificación).
     * @param string $description Descripción del pago.
     * @param int $installments Número de cuotas.
     * @param string $externalReference Referencia externa para tu orden.
     * @return object Un objeto de Pago de Mercado Pago.
     * @throws \MercadoPago\Exceptions\MPApiException Si hay un error con la API de MP.
     * @throws \Exception Si ocurre otro error.
     */
    public function procesarPagoDirecto(float $amount, string $token, array $payerData, string $description, int $installments, string $externalReference): object;

    /**
     * Maneja las notificaciones de webhook de Mercado Pago.
     * @param array $data Los datos del webhook (el request de MP).
     * @return bool True si la notificación fue procesada correctamente.
     */
    public function handleWebhook(array $data): bool;
}