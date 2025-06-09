<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SkydropxService; // <--- ¡Asegúrate de que esta línea esté presente y sea correcta!
use App\Models\CartItem; // Asumo que tienes un modelo para los ítems del carrito
use App\Models\Product; // O Artesania, para obtener detalles del producto
use App\Models\UserAddress; // Asumo que los usuarios pueden tener direcciones guardadas
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Para manejar datos en la sesión
class CheckoutController extends Controller
{
     protected $skydropxService;
    protected $oaxacaOriginAddress; // Para la dirección de origen predefinida

    public function __construct(SkydropxService $skydropxService)
    {
        $this->skydropxService = $skydropxService;

        // Definir la dirección de origen para SkydropX (tu ubicación en Oaxaca)
        // Puedes poner esto en un archivo de configuración si prefieres
        $this->oaxacaOriginAddress = [
            'country_code' => 'MX',
            'postal_code' => '68000', // Código postal central de Oaxaca de Juárez
            'area_level1' => 'Oaxaca', // Estado
            'area_level2' => 'Oaxaca de Juárez', // Ciudad/Municipio
            'area_level3' => 'Centro', // Colonia/Barrio (puedes ajustar esto o dejarlo más general)
            'name' => 'Raices Artesanales MX',
            'address1' => 'Calle Reforma 123', // Dirección de tu tienda/almacén en Oaxaca
            'phone' => '9511234567', // Tu número de teléfono
            'company' => 'Raices Artesanales MX',
            'reference' => 'Frente al templo de Santo Domingo', // Referencia opcional
        ];
    }

     /**
     * Muestra la página donde el usuario puede seleccionar/ingresar su dirección
     * y ver las opciones de envío.
     */

    public function showShippingOptions(Resquest $request){
        //verificar si el usuario esta autenticado 
        if(!Auth::check()){
            return redirect()->route('login')->with('info', 'Por favor, inicia sesión para continuar con tu compra.');
        }
        //obtener los items del carrito 
        $carItems = CartItem::where('user_id', Auth::id())->with('error', 'Tu carrito esta vacio');

        if($carItems ->isEmpety()){
            if ($cartItems->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }
        }
        // Calcular el peso y las dimensiones totales del carrito
        // Esto es una simplificación; en un sistema real, cada producto tendría su peso/dimensiones
        // y deberías sumarlos o agruparlos en paquetes lógicos.
        $totalWeight = 0;
        $totalLength = 0; // Usaremos el máximo para simplificar, o podrías usar un cálculo de volumen
        $totalWidth = 0;
        $totalHeight = 0;
        $subtotal = 0;

        foreach ($cartItems as $item) {
            // Asumiendo que artesania tiene peso y dimensiones
            // Si no las tienes, necesitarás campos en tu tabla 'artesanias'
            $artesania = $item->artesania;
            $totalWeight += ($artesania->weight ?? 0.1) * $item->quantity; // Peso en KG
            $totalLength = max($totalLength, $artesania->length ?? 10); // CM
            $totalWidth = max($totalWidth, $artesania->width ?? 10);   // CM
            $totalHeight = max($totalHeight, $artesania->height ?? 10); // CM
            $subtotal += $item->quantity * $artesania->precio;
        }

        // Establecer un peso/dimensiones mínimos si el total es muy bajo
        $totalWeight = max($totalWeight, 0.1); // Min 100g
        $totalLength = max($totalLength, 10);
        $totalWidth = max($totalWidth, 10);
        $totalHeight = max($totalHeight, 10);

        $parcel = [
            'weight' => (float)number_format($totalWeight, 2), // SkydropX espera float
            'distance_unit' => 'CM',
            'width' => (int)ceil($totalWidth), // SkydropX espera enteros
            'height' => (int)ceil($totalHeight),
            'length' => (int)ceil($totalLength),
            'mass_unit' => 'KG'
        ];

        // 3. Obtener direcciones guardadas del usuario (si existen)
        $userAddresses = Auth::user()->addresses; // Asumo que el modelo User tiene relación hasMany con UserAddress

        // Pre-cargar la dirección del usuario si tiene una "principal" o la más reciente
        $defaultAddress = $userAddresses->first(); // O puedes tener lógica para una dirección por defecto

        // Intentar obtener las cotizaciones si ya hay una dirección por defecto
        $shippingOptions = [];
        $quotationId = null;

        if ($defaultAddress) {
            $addressTo = [
                'country_code' => $defaultAddress->country_code,
                'postal_code' => $defaultAddress->postal_code,
                'area_level1' => $defaultAddress->state,
                'area_level2' => $defaultAddress->city,
                'area_level3' => $defaultAddress->colony, // Asegúrate de tener este campo
                // Otros campos de dirección de SkydropX no son necesarios para la cotización
            ];

            $quotationResult = $this->skydropxService->createQuotation(
                $this->oaxacaOriginAddress,
                $addressTo,
                $parcel,
                'order_temp_' . Auth::id() . '_' . time() // order_id único para la cotización
            );

            if (isset($quotationResult['rates']) && !empty($quotationResult['rates'])) {
                $shippingOptions = collect($quotationResult['rates'])
                                    ->filter(function($rate) {
                                        // Filtrar solo tarifas exitosas y con cobertura
                                        return $rate['success'] && in_array($rate['status'], ['approved', 'price_found_internal', 'price_found_external']);
                                    })
                                    ->sortBy('total') // Ordenar por costo
                                    ->values() // Reindexar el array
                                    ->all();
                $quotationId = $quotationResult['id'];
                // Guardar el quotation_id en la sesión para usarlo más tarde
                Session::put('skydropx_quotation_id', $quotationId);
                Session::put('skydropx_parcel_data', $parcel); // Guardar datos del paquete
                Session::put('skydropx_address_from', $this->oaxacaOriginAddress); // Guardar dirección origen
                Session::put('skydropx_address_to', $addressTo); // Guardar dirección destino (sin todos los detalles de address1, phone, etc. aún)

            } elseif (isset($quotationResult['error'])) {
                Log::error("SkydropX Quotation Error for user " . Auth::id() . ": " . json_encode($quotationResult['error']));
                Session::flash('error', 'No fue posible cotizar el envío a tu dirección actual. Por favor, revisa tus datos o intenta más tarde.');
            } else {
                 Session::flash('info', 'No se encontraron opciones de envío para la dirección predeterminada. Ingresa una nueva dirección o ajusta la existente.');
            }
        }

        return view('checkout.shipping', compact(
            'cartItems',
            'subtotal',
            'userAddresses',
            'defaultAddress',
            'shippingOptions',
            'quotationId',
            'parcel'
        ));
    }

    /**
     * Procesa el envío de la dirección y cotiza las opciones.
     * Esto se llamaría vía AJAX o un submit de formulario desde la vista de shipping.
     */
    public function getShippingQuotes(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string|max:2',
            'postal_code' => 'required|string|max:10',
            'state' => 'required|string|max:255', // area_level1
            'city' => 'required|string|max:255', // area_level2
            'colony' => 'required|string|max:255', // area_level3
            // No se necesitan address1, phone, name para la cotización inicial, pero sí para crear el envío
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->with('artesania')->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Tu carrito está vacío.'], 400);
        }

        $totalWeight = 0;
        $totalLength = 0;
        $totalWidth = 0;
        $totalHeight = 0;

        foreach ($cartItems as $item) {
            $artesania = $item->artesania;
            $totalWeight += ($artesania->weight ?? 0.1) * $item->quantity;
            $totalLength = max($totalLength, $artesania->length ?? 10);
            $totalWidth = max($totalWidth, $artesania->width ?? 10);
            $totalHeight = max($totalHeight, $artesania->height ?? 10);
        }

        $totalWeight = max($totalWeight, 0.1);
        $totalLength = max($totalLength, 10);
        $totalWidth = max($totalWidth, 10);
        $totalHeight = max($totalHeight, 10);

        $parcel = [
            'weight' => (float)number_format($totalWeight, 2),
            'distance_unit' => 'CM',
            'width' => (int)ceil($totalWidth),
            'height' => (int)ceil($totalHeight),
            'length' => (int)ceil($totalLength),
            'mass_unit' => 'KG'
        ];

        $addressTo = [
            'country_code' => $request->country_code,
            'postal_code' => $request->postal_code,
            'area_level1' => $request->state,
            'area_level2' => $request->city,
            'area_level3' => $request->colony,
        ];

        $quotationResult = $this->skydropxService->createQuotation(
            $this->oaxacaOriginAddress,
            $addressTo,
            $parcel,
            'order_temp_' . Auth::id() . '_' . time()
        );

        if (isset($quotationResult['rates'])) {
            $shippingOptions = collect($quotationResult['rates'])
                                ->filter(function($rate) {
                                    return $rate['success'] && in_array($rate['status'], ['approved', 'price_found_internal', 'price_found_external']);
                                })
                                ->sortBy('total')
                                ->values()
                                ->all();
            $quotationId = $quotationResult['id'];

            // Guardar el quotation_id y datos del paquete en la sesión
            Session::put('skydropx_quotation_id', $quotationId);
            Session::put('skydropx_parcel_data', $parcel);
            Session::put('skydropx_address_from', $this->oaxacaOriginAddress);
            Session::put('skydropx_address_to', $addressTo); // Solo los datos de cotización

            return response()->json([
                'success' => true,
                'shippingOptions' => $shippingOptions,
                'quotationId' => $quotationId,
                'parcel' => $parcel // Devolver también el paquete calculado
            ]);
        } elseif (isset($quotationResult['error'])) {
            Log::error("SkydropX Quotation Error in getShippingQuotes: " . json_encode($quotationResult['error']));
            return response()->json([
                'success' => false,
                'message' => 'No fue posible cotizar el envío a esta dirección. Por favor, revisa tus datos o intenta más tarde.',
                'errors' => $quotationResult['error']
            ], 500);
        } else {
             return response()->json([
                'success' => false,
                'message' => 'No se encontraron opciones de envío para la dirección proporcionada. Intenta con otra o ajusta los detalles.',
            ], 404);
        }
    }


    /**
     * Almacena la dirección completa del usuario y la tarifa de envío seleccionada
     * y redirige a la página de pago.
     */
    public function processShippingSelection(Request $request)
    {
        $request->validate([
            'selected_shipping_rate_id' => 'required|string',
            'full_address1' => 'required|string|max:255',
            'full_address2' => 'nullable|string|max:255', // Opcional
            'phone' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'save_address' => 'boolean', // Checkbox para guardar dirección
        ]);

        $quotationId = Session::get('skydropx_quotation_id');
        $skydropxAddressTo = Session::get('skydropx_address_to'); // Esto tiene area_level1, area_level2, etc.

        if (!$quotationId || !$skydropxAddressTo) {
            return redirect()->route('checkout.shipping')->with('error', 'No se pudo recuperar la cotización de envío. Por favor, intenta de nuevo.');
        }

        // Obtener la cotización completa desde SkydropX para validar la tarifa
        $quotationDetails = $this->skydropxService->getQuotation($quotationId);

        if (!isset($quotationDetails['rates'])) {
             return redirect()->route('checkout.shipping')->with('error', 'La cotización de envío no es válida o expiró. Por favor, vuelve a seleccionar una opción.');
        }

        $selectedRate = collect($quotationDetails['rates'])->firstWhere('id', $request->selected_shipping_rate_id);

        if (!$selectedRate) {
            return redirect()->route('checkout.shipping')->with('error', 'La opción de envío seleccionada no es válida.');
        }

        // Combinar los datos de address_to de la sesión con los detalles completos del formulario
        $finalAddressTo = array_merge($skydropxAddressTo, [
            'name' => $request->name,
            'address1' => $request->full_address1,
            'address2' => $request->full_address2,
            'phone' => $request->phone,
            'company' => $request->company,
            'reference' => $request->reference,
        ]);


        // Guardar la dirección del usuario si lo marcó
        if ($request->save_address) {
            Auth::user()->addresses()->updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'full_address1' => $request->full_address1, // Considera un identificador único para la dirección
                ],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address1' => $request->full_address1,
                    'address2' => $request->full_address2,
                    'country_code' => $finalAddressTo['country_code'],
                    'postal_code' => $finalAddressTo['postal_code'],
                    'state' => $finalAddressTo['area_level1'],
                    'city' => $finalAddressTo['area_level2'],
                    'colony' => $finalAddressTo['area_level3'],
                    // Otros campos que guardes
                ]
            );
        }

        // Guardar la información de envío en la sesión para el pago
        Session::put('checkout_shipping_info', [
            'selected_rate' => $selectedRate,
            'quotation_id' => $quotationId,
            'address_to_details' => $finalAddressTo, // Detalles completos de la dirección del cliente
        ]);

        return redirect()->route('checkout.payment');
    }


    }
    


