<?php
namespace App\Services; // <--- ¡Asegúrate de que este sea el correcto!
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
class SkydropxService {
    protected $client;
    protected $apiKey;
    protected $baseUrl;


    public function __construct(){
        $this->apiKey=config('services.skydropx.api_token');

        $this->baseUrl= config('services.skydropx.base_url');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey, // Usamos Bearer Token como en la documentación
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'verify' => false, // Deshabilitar la verificación SSL para desarrollo si tienes problemas con certificados
        ]);
    }


    /**
     * Crea una cotización con SkydropX.
     * Endpoint: POST /api/v1/quotations
     *
     * @param array $addressFrom Datos de la dirección de origen.
     * @param array $addressTo Datos de la dirección de destino.
     * @param array $parcel Datos del paquete (length, width, height, weight).
     * @param string|null $orderId ID de la orden interna para referenciar la cotización.
     * @param array $requestedCarriers Lista de paqueterías a cotizar (opcional).
     * @return array|null Retorna la respuesta de la API o un array con error.
     */
    public function createQuotation(
        array $addressFrom,
        array $addressTo,
        array $parcel,
        ?string $orderId = null,
        array $requestedCarriers = []
    ): ?array {
        $data = [
            'address_from' => $addressFrom,
            'address_to' => $addressTo,
            'parcel' => $parcel,
        ];

        if ($orderId) {
            $data['order_id'] = $orderId;
        }
        if (!empty($requestedCarriers)) {
            $data['requested_carriers'] = $requestedCarriers;
        }

        try {
            // Esto es equivalente a la petición cURL que mostraste
            $response = $this->client->post('quotations', [
                'json' => $data, // Guzzle serializa $data a JSON y establece Content-Type: application/json
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return $result;

        } catch (ClientException $e) {
            $responseBody = $e->getResponse()->getBody(true);
            $errorMessage = json_decode($responseBody, true) ?: $e->getMessage();
            Log::error('SkydropX Client Error (createQuotation): ' . $e->getMessage() . ' - Response: ' . $responseBody);
            return ['error' => $errorMessage, 'status_code' => $e->getResponse()->getStatusCode()];
        } catch (\Exception $e) {
            Log::error('SkydropX Error (createQuotation): ' . $e->getMessage());
            return ['error' => 'Error al crear la cotización.', 'message' => $e->getMessage()];
        }
    }



}