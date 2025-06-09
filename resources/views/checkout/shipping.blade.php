@extends('layouts.app') {{-- Asume que tienes un layout base llamado 'app' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center" style="font-family: 'Montserrat', sans-serif;">Paso 2: Dirección y Envío</h1>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">¡Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">¡Información!</strong>
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Columna del Carrito (Resumen) --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200" style="border-color: #A34A2A;">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Resumen del Carrito</h2>
            @foreach ($cartItems as $item)
                <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
                    <div>
                        <p class="font-medium text-gray-700">{{ $item->artesania->nombre }}</p>
                        <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                    </div>
                    <p class="font-semibold text-gray-800">${{ number_format($item->subtotal, 2) }}</p>
                </div>
            @endforeach
            <div class="flex justify-between items-center mt-4">
                <p class="text-xl font-bold text-gray-900">Subtotal:</p>
                <p class="text-xl font-bold text-gray-900">${{ number_format($subtotal, 2) }}</p>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <p><strong>Peso estimado:</strong> {{ number_format($parcel['weight'], 2) }} KG</p>
                <p><strong>Dimensiones estimadas:</strong> {{ $parcel['length'] }}x{{ $parcel['width'] }}x{{ $parcel['height'] }} CM</p>
                <p class="mt-2 text-red-500">Las tarifas de envío se calcularán una vez que ingreses tu dirección.</p>
            </div>
        </div>

        {{-- Columna de Dirección y Opciones de Envío --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md border border-gray-200" style="border-color: #A34A2A;">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Dirección de Envío</h2>

            <form id="shipping-form" method="POST" action="{{ route('checkout.process_shipping') }}">
                @csrf

                {{-- Sección para seleccionar dirección guardada --}}
                @if ($userAddresses->isNotEmpty())
                    <div class="mb-6">
                        <label for="saved_address" class="block text-gray-700 text-sm font-bold mb-2">Seleccionar dirección guardada:</label>
                        <select id="saved_address" name="saved_address_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="loadSavedAddress()">
                            <option value="">-- Seleccionar una dirección --</option>
                            @foreach ($userAddresses as $address)
                                <option value="{{ $address->id }}"
                                    data-country-code="{{ $address->country_code }}"
                                    data-postal-code="{{ $address->postal_code }}"
                                    data-state="{{ $address->state }}"
                                    data-city="{{ $address->city }}"
                                    data-colony="{{ $address->colony }}"
                                    data-name="{{ $address->name }}"
                                    data-phone="{{ $address->phone }}"
                                    data-address1="{{ $address->address1 }}"
                                    data-address2="{{ $address->address2 }}"
                                    data-reference="{{ $address->reference }}"
                                    {{ ($defaultAddress && $defaultAddress->id == $address->id) ? 'selected' : '' }}>
                                    {{ $address->address1 }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }} ({{ $address->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6 text-center text-gray-600">
                        <p class="text-sm">O ingresa una nueva dirección:</p>
                    </div>
                @endif

                {{-- Campos para la dirección nueva --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo del Receptor <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name', $defaultAddress->name ?? '') }}" required>
                        @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Teléfono <span class="text-red-500">*</span></label>
                        <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror" value="{{ old('phone', $defaultAddress->phone ?? '') }}" required>
                        @error('phone') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="full_address1" class="block text-gray-700 text-sm font-bold mb-2">Calle, Número Exterior e Interior <span class="text-red-500">*</span></label>
                    <input type="text" id="full_address1" name="full_address1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('full_address1') border-red-500 @enderror" value="{{ old('full_address1', $defaultAddress->address1 ?? '') }}" placeholder="Ej: Av. Juárez #123, Int. 45" required>
                    @error('full_address1') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="address2" class="block text-gray-700 text-sm font-bold mb-2">Colonia o Barrio <span class="text-red-500">*</span></label>
                    <input type="text" id="colony" name="colony" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('colony') border-red-500 @enderror" value="{{ old('colony', $defaultAddress->colony ?? '') }}" required>
                    @error('colony') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="postal_code" class="block text-gray-700 text-sm font-bold mb-2">Código Postal <span class="text-red-500">*</span></label>
                        <input type="text" id="postal_code" name="postal_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('postal_code') border-red-500 @enderror" value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}" required>
                        @error('postal_code') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Ciudad o Municipio <span class="text-red-500">*</span></label>
                        <input type="text" id="city" name="city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror" value="{{ old('city', $defaultAddress->city ?? '') }}" required>
                        @error('city') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="state" class="block text-gray-700 text-sm font-bold mb-2">Estado <span class="text-red-500">*</span></label>
                        <input type="text" id="state" name="state" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('state') border-red-500 @enderror" value="{{ old('state', $defaultAddress->state ?? '') }}" required>
                        @error('state') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="country_code" class="block text-gray-700 text-sm font-bold mb-2">País <span class="text-red-500">*</span></label>
                        {{-- Idealmente esto sería un select con países, pero para SkydropX México, podrías fijarlo --}}
                        <input type="text" id="country_code" name="country_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('country_code') border-red-500 @enderror" value="{{ old('country_code', $defaultAddress->country_code ?? 'MX') }}" required readonly>
                        @error('country_code') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="reference" class="block text-gray-700 text-sm font-bold mb-2">Referencias Adicionales (color de casa, entre calles)</label>
                    <textarea id="reference" name="reference" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('reference') border-red-500 @enderror" rows="2">{{ old('reference', $defaultAddress->reference ?? '') }}</textarea>
                    @error('reference') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="save_address" class="form-checkbox" checked>
                        <span class="ml-2 text-gray-700 text-sm">Guardar esta dirección para futuras compras</span>
                    </label>
                </div>

                <div class="text-center mb-6">
                    <button type="button" id="get-quotes-btn" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 ease-in-out" style="background-color: #CD6133;">
                        Actualizar Opciones de Envío
                    </button>
                    <div id="loading-spinner" class="hidden text-gray-600 mt-2">
                        <svg class="animate-spin h-5 w-5 text-gray-500 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Calculando tarifas...
                    </div>
                </div>

                {{-- Opciones de Envío --}}
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Opciones de Envío Disponibles</h3>
                <div id="shipping-options-container" class="space-y-4 mb-6">
                    @if (!empty($shippingOptions))
                        @foreach ($shippingOptions as $option)
                            <label class="flex items-center p-4 border rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out">
                                <input type="radio" name="selected_shipping_rate_id" value="{{ $option['id'] }}" class="form-radio h-5 w-5 text-indigo-600" required>
                                <div class="ml-4 flex-grow">
                                    <p class="text-lg font-medium text-gray-900">{{ $option['provider_name'] }} - {{ $option['provider_service_name'] }}</p>
                                    <p class="text-sm text-gray-600">Entrega en {{ $option['days'] }} días hábiles.</p>
                                    @if ($option['status'] !== 'approved')
                                        <p class="text-sm text-red-500">Estado: {{ $option['status'] }}</p>
                                    @endif
                                </div>
                                <span class="text-lg font-bold text-gray-800">${{ number_format($option['total'], 2) }}</span>
                            </label>
                        @endforeach
                    @else
                        <p id="no-shipping-options" class="text-gray-600 text-center">Ingresa o selecciona una dirección para ver las opciones de envío.</p>
                    @endif
                </div>
                <input type="hidden" id="quotation_id" name="quotation_id" value="{{ $quotationId }}">


                <div class="flex justify-end mt-8">
                    <button type="submit" id="continue-to-payment" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out text-lg" style="background-color: #6a994e;">
                        Continuar al Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts') {{-- Asume que tienes una sección 'scripts' en tu layout --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const getQuotesBtn = document.getElementById('get-quotes-btn');
        const shippingOptionsContainer = document.getElementById('shipping-options-container');
        const loadingSpinner = document.getElementById('loading-spinner');
        const noShippingOptionsMessage = document.getElementById('no-shipping-options');
        const savedAddressSelect = document.getElementById('saved_address');
        const form = document.getElementById('shipping-form');

        // Función para cargar datos de dirección guardada
        window.loadSavedAddress = function() {
            const selectedOption = savedAddressSelect.options[savedAddressSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                document.getElementById('name').value = selectedOption.dataset.name || '';
                document.getElementById('phone').value = selectedOption.dataset.phone || '';
                document.getElementById('full_address1').value = selectedOption.dataset.address1 || '';
                document.getElementById('colony').value = selectedOption.dataset.colony || '';
                document.getElementById('postal_code').value = selectedOption.dataset.postalCode || '';
                document.getElementById('city').value = selectedOption.dataset.city || '';
                document.getElementById('state').value = selectedOption.dataset.state || '';
                document.getElementById('country_code').value = selectedOption.dataset.countryCode || 'MX';
                document.getElementById('reference').value = selectedOption.dataset.reference || '';

                // Autocotizar si se carga una dirección guardada
                getQuotes();
            } else {
                // Si se selecciona la opción "Seleccionar una dirección", limpiar campos
                // Podrías decidir si quieres limpiar los campos o no.
                // document.getElementById('name').value = '';
                // ... y así con los demás.
            }
        };


        getQuotesBtn.addEventListener('click', getQuotes);

        async function getQuotes() {
            const countryCode = document.getElementById('country_code').value;
            const postalCode = document.getElementById('postal_code').value;
            const state = document.getElementById('state').value;
            const city = document.getElementById('city').value;
            const colony = document.getElementById('colony').value;

            if (!countryCode || !postalCode || !state || !city || !colony) {
                alert('Por favor, completa todos los campos de dirección requeridos (País, C.P., Estado, Ciudad, Colonia) para cotizar el envío.');
                return;
            }

            // Mostrar spinner y ocultar opciones anteriores
            loadingSpinner.classList.remove('hidden');
            shippingOptionsContainer.innerHTML = ''; // Limpiar opciones anteriores
            if (noShippingOptionsMessage) noShippingOptionsMessage.classList.add('hidden'); // Ocultar mensaje

            try {
                const response = await fetch('{{ route('checkout.get_quotes') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        country_code: countryCode,
                        postal_code: postalCode,
                        state: state,
                        city: city,
                        colony: colony,
                        // No necesitamos enviar full_address1, phone, name aquí para la cotización
                        // Esos son para guardar la dirección completa en processShippingSelection
                    })
                });

                const data = await response.json();

                if (data.success) {
                    if (data.shippingOptions.length > 0) {
                        data.shippingOptions.forEach(option => {
                            const radioHtml = `
                                <label class="flex items-center p-4 border rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <input type="radio" name="selected_shipping_rate_id" value="${option.id}" class="form-radio h-5 w-5 text-indigo-600" required>
                                    <div class="ml-4 flex-grow">
                                        <p class="text-lg font-medium text-gray-900">${option.provider_name} - ${option.provider_service_name}</p>
                                        <p class="text-sm text-gray-600">Entrega en ${option.days} días hábiles.</p>
                                        ${option.status !== 'approved' ? `<p class="text-sm text-red-500">Estado: ${option.status}</p>` : ''}
                                    </div>
                                    <span class="text-lg font-bold text-gray-800">$${(option.total || 0).toFixed(2)}</span>
                                </label>
                            `;
                            shippingOptionsContainer.insertAdjacentHTML('beforeend', radioHtml);
                        });
                        // Actualizar el quotation_id en el campo oculto
                        document.getElementById('quotation_id').value = data.quotationId;

                    } else {
                        shippingOptionsContainer.innerHTML = `<p class="text-gray-600 text-center">No se encontraron opciones de envío para esta dirección.</p>`;
                    }
                } else {
                    alert('Error al cotizar el envío: ' + (data.message || 'Error desconocido.'));
                    shippingOptionsContainer.innerHTML = `<p class="text-red-600 text-center">Error al cargar opciones de envío.</p>`;
                }
            } catch (error) {
                console.error('Error fetching shipping quotes:', error);
                alert('Ocurrió un error al intentar cotizar el envío. Por favor, intenta de nuevo.');
                shippingOptionsContainer.innerHTML = `<p class="text-red-600 text-center">Error de conexión al cotizar envío.</p>`;
            } finally {
                loadingSpinner.classList.add('hidden');
            }
        }

        // Si hay una dirección por defecto cargada, intentar cargar las cotizaciones
        if (savedAddressSelect && savedAddressSelect.value) {
            loadSavedAddress(); // Esto disparará la carga de cotizaciones si hay una dirección seleccionada
        } else {
            // Si no hay direcciones guardadas o no hay seleccionada por defecto,
            // y si el servidor ya envió cotizaciones (ej. si hubo errores de validación en el POST y se recargó la página)
            if (shippingOptionsContainer.children.length === 0 && noShippingOptionsMessage) {
                noShippingOptionsMessage.classList.remove('hidden');
            }
        }
    });
</script>
@endpush