<?php

namespace App\Http\Controllers;

use App\Services\SkydropxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SkydropxTestController extends Controller
{
    protected SkydropxService $skydropxService;

    public function __construct(SkydropxService $skydropxService)
    {
        $this->skydropxService = $skydropxService;
    }

    public function testQuotation()
    {
        $addressFrom = [
            "province" => "CDMX",
            "city" => "Ciudad de México",
            "name" => "Remitente Demo",
            "postal_code" => "01000", // <-- ¡CORREGIDO: de 'zip' a 'postal_code'!
            "country_code" => "MX", // <-- ¡CORREGIDO: de 'country' a 'country_code'!
            "address1" => "Av. Insurgentes Sur 1234",
            "company" => "Mi Empresa S.A. de C.V.",
            "address2" => "Piso 5, Oficina 501",
            "phone" => "5512345678",
            "email" => "remitente@ejemplo.com",
        ];

        $addressTo = [
            "province" => "Jalisco",
            "city" => "Guadalajara",
            "name" => "Destinatario Demo",
            "postal_code" => "44100", // <-- ¡CORREGIDO: de 'zip' a 'postal_code'!
            "country_code" => "MX", // <-- ¡CORREGIDO: de 'country' a 'country_code'!
            "address1" => "Av. Chapultepec Norte 567",
            "company" => "Cliente Ejemplo S.A. de C.V.",
            "address2" => "Local 2",
            "phone" => "3398765432",
            "email" => "destinatario@ejemplo.com",
        ];

        $parcel = [
            "length" => 10,
            "width" => 10,
            "height" => 10,
            "weight" => 1.5,
            "distance_unit" => "CM",
            "mass_unit" => "KG"
        ];
        try {
            $quotation = $this->skydropxService->createQuotation(
                $addressFrom,
                $addressTo,
                $parcel,
                'ORDEN_TEST_123'
            );
            Log::info('SkydropX Quotation success:', $quotation);
            return response()->json([
                'status' => 'success',
                'message' => 'Cotización creada exitosamente.',
                'data' => $quotation
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            Log::error('SkydropX API Error:', ['message' => $e->getMessage(), 'response' => $responseBody]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error de la API de SkydropX.',
                'details' => $responseBody
            ], $e->getResponse()->getStatusCode());

        } catch (\Exception $e) {
            Log::error('General SkydropX Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado al cotizar.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}