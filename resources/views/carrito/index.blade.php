@extends('layouts.public') {{-- Asegúrate de que este layout incluya Tailwind CSS --}}

@section('title', 'Tu Carrito de Compras - Raíces Artesanales MX')

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Carga el SDK de Mercado Pago v2 --}}
    <script src="https://sdk.mercadopago.com/js/v2"></script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">

    <h1 class="text-4xl font-extrabold text-oaxaca-title-pink mb-8 text-center">Tu Carrito de Compras</h1>

    {{-- Mensajes de Sesión --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">¡Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($cartItems->isEmpty()) {{-- Ahora verificamos si la colección de ítems está vacía --}}
        <div class="bg-white p-10 rounded-lg shadow-lg text-center">
            <p class="text-xl text-gray-700 mb-4">Tu carrito está vacío. ¡Explora nuestras artesanías!</p>
            <a href="{{ route('artesanias.index') }}" class="inline-block bg-oaxaca-button-mustard text-oaxaca-text-dark-gray font-semibold px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition duration-300">
                Ver Catálogo
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Columna Izquierda: Detalles del Carrito --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($cartItems as $item) {{-- Ahora iteramos sobre $cartItems --}}
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-20 w-20">
                                                <img class="h-20 w-20 rounded-lg object-cover" src="{{ asset('storage/' . $item->artesania->imagen_principal) }}" alt="{{ $item->artesania->nombre }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->artesania->nombre }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($item->price, 2) }} {{-- Usar el precio guardado en cart_item --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('carrito.actualizar') }}" method="POST" class="flex items-center">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}"> {{-- ID del CartItem --}}
                                            <input type="number"
                                                   name="cantidad"
                                                   value="{{ $item->quantity }}"
                                                   min="0" {{-- Permitir 0 para eliminar el ítem --}}
                                                   class="w-20 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-oaxaca-navbar-blue focus:border-oaxaca-navbar-blue sm:text-sm"
                                                   onchange="this.form.submit()"> {{-- Envía el form al cambiar la cantidad --}}
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($item->subtotal, 2) }} {{-- Usar el accesor subtotal --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('carrito.remover') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}"> {{-- ID del CartItem --}}
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition duration-300">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Botón para vaciar el carrito --}}
                <div class="mt-8 text-right">
                    <form action="{{ route('carrito.vaciar') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-300 text-base font-semibold shadow-md">
                            Vaciar Carrito
                        </button>
                    </form>
                </div>
            </div>

            {{-- Columna Derecha: Resumen de la Orden y Pago Transparente --}}
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-lg h-fit">
                <h2 class="text-2xl font-bold text-oaxaca-navbar-blue mb-6">Resumen de la Orden</h2>

                <div class="flex justify-between items-center text-gray-700 mb-4">
                    <span class="text-lg">Subtotal:</span>
                    <span class="text-lg font-semibold">${{ number_format($total, 2) }} MXN</span>
                </div>
                <div class="border-t border-gray-200 my-4"></div>

                <div class="flex justify-between items-center text-oaxaca-title-pink mb-6">
                    <span class="text-2xl font-bold">Total:</span>
                    <span class="text-2xl font-bold">${{ number_format($total, 2) }} MXN</span>
                </div>

                <p class="text-sm text-gray-600 mb-6">El envío y los impuestos se calcularán en el siguiente paso.</p>

                {{-- Formulario de Pago con Tarjeta de Mercado Pago (Checkout Transparente) --}}
                <div class="mt-6">
                    <h3 class="text-xl font-bold text-oaxaca-navbar-blue mb-4">Pagar con Tarjeta</h3>

                    @if (!Auth::check())
                        <p class="text-oaxaca-text-dark-gray mb-4">
                            Por favor, <a href="{{ route('login') }}" class="text-oaxaca-navbar-blue hover:underline font-semibold">inicia sesión</a> para proceder al pago.
                        </p>
                    @else
                        <form id="payment-form-carrito" class="bg-gray-50 p-6 rounded-lg shadow-inner">
                            @csrf
                            {{-- No necesitamos inputs ocultos para artesania_id o cantidad aquí, el backend los obtendrá del carrito del usuario --}}

                            <div class="mb-4">
                                <label for="card-number" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">Número de Tarjeta:</label>
                                <div id="form-card-number" class="border border-gray-300 rounded-lg p-3 bg-white focus-within:ring-2 focus-within:ring-oaxaca-navbar-blue focus-within:border-oaxaca-navbar-blue"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="form-expiration-date" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">Fecha de Vencimiento (MM/AA):</label>
                                    <div id="form-expiration-date" class="border border-gray-300 rounded-lg p-3 bg-white focus-within:ring-2 focus-within:ring-oaxaca-navbar-blue focus-within:border-oaxaca-navbar-blue"></div>
                                </div>
                                <div>
                                    <label for="form-cvv" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">CVV:</label>
                                    <div id="form-cvv" class="border border-gray-300 rounded-lg p-3 bg-white focus-within:ring-2 focus-within:ring-oaxaca-navbar-blue focus-within:border-oaxaca-navbar-blue"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="card-holder-name" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">Nombre del Titular:</label>
                                <input type="text" id="card-holder-name" name="card_holder_name" placeholder="Nombre completo del titular"
                                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-oaxaca-navbar-blue focus:border-oaxaca-navbar-blue" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="doc-type" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">Tipo de Documento:</label>
                                    <select id="doc-type" name="doc_type"
                                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-oaxaca-navbar-blue focus:border-oaxaca-navbar-blue" required>
                                        <option value="">Selecciona</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="doc-number" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">Número de Documento:</label>
                                    <input type="text" id="doc-number" name="doc_number" placeholder="Número de documento"
                                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-oaxaca-navbar-blue focus:border-oaxaca-navbar-blue" required>
                                </div>
                            </div>

                            <input type="hidden" id="card-token-carrito" name="card_token"> {{-- Aquí se guardará el token de la tarjeta --}}
                            <input type="hidden" name="total_amount" value="{{ number_format($total, 2, '.', '') }}"> {{-- Envía el total del carrito --}}

                            <button type="submit" id="submit-button-carrito"
                                    class="w-full inline-flex items-center justify-center bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-4 rounded-lg hover:bg-oaxaca-button-mustard-hover transition duration-300 text-xl font-semibold shadow-lg">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Pagar Carrito ${{ number_format($total, 2) }}
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Espacio para opciones de pago (íconos de Mercado Pago, Visa, Mastercard, etc.) --}}
                <div class="mt-6 text-center text-gray-500 text-sm">
                    Aceptamos: <span class="font-semibold text-oaxaca-navbar-blue">Mercado Pago, Visa, Mastercard</span>
                    {{-- Aquí podrías poner pequeños íconos de las tarjetas --}}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Inicializa el SDK de Mercado Pago con tu Public Key
    const mp = new MercadoPago('{{ config('services.mercadopago.public_key') }}');

    // Solo inicializa el formulario de pago si el carrito no está vacío y el usuario está autenticado
    @if (!$cartItems->isEmpty() && Auth::check())
        const cardFormCarrito = mp.fields.create({
            cardholderName: {
                id: "card-holder-name",
                placeholder: "Nombre y Apellido",
            },
            cardNumber: {
                id: "form-card-number",
                placeholder: "Número de Tarjeta",
            },
            expirationDate: {
                id: "form-expiration-date",
                placeholder: "MM/AA",
            },
            securityCode: {
                id: "form-cvv",
                placeholder: "CVV",
            },
        }).render();

        // Función para cargar los tipos de documento
        (async function getIdentificationTypesCarrito() {
            try {
                const identificationTypes = await mp.getIdentificationTypes();
                const docTypeElement = document.getElementById('doc-type');

                docTypeElement.innerHTML = '<option value="">Selecciona</option>';
                identificationTypes.forEach(identification => {
                    const opt = document.createElement('option');
                    opt.value = identification.id;
                    opt.textContent = identification.name;
                    docTypeElement.appendChild(opt);
                });
            } catch (e) {
                console.error('Error al obtener tipos de documento para carrito:', e);
                alert('No se pudieron cargar los tipos de documento. Por favor, recarga la página.');
            }
        })();

        // Manejar el envío del formulario de pago del carrito
        const formCarrito = document.getElementById('payment-form-carrito');
        const submitButtonCarrito = document.getElementById('submit-button-carrito');

        formCarrito.addEventListener('submit', async (e) => {
            e.preventDefault();

            submitButtonCarrito.disabled = true;
            submitButtonCarrito.textContent = 'Procesando pago del carrito...';

            try {
                // Crea el token de la tarjeta
                const tokenResponse = await mp.fields.createCardToken({
                    cardholderName: cardFormCarrito.cardholderName.value,
                    identificationType: document.getElementById('doc-type').value,
                    identificationNumber: document.getElementById('doc-number').value,
                });

                document.getElementById('card-token-carrito').value = tokenResponse.id;

                const formData = new FormData(formCarrito);

                // Asegúrate de que este sea el endpoint correcto para procesar el pago del carrito
                const response = await fetch('{{ route('mercadopago.procesar_pago_carrito_transparente') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const result = await response.json();

                if (response.ok) {
                    if (result.success) {
                        alert('¡Pago ' + (result.status === 'approved' ? 'aprobado' : 'pendiente') + '!');
                        window.location.href = result.redirect_url;
                    } else {
                        alert('Error en el pago: ' + (result.message || 'Error desconocido.'));
                    }
                } else {
                    alert('Error en la solicitud: ' + (result.message || 'Error desconocido del servidor.'));
                }

            } catch (error) {
                console.error('Error en el proceso de pago del carrito:', error);
                alert('Ocurrió un error inesperado al procesar el pago del carrito. Por favor, intenta de nuevo.');
            } finally {
                submitButtonCarrito.disabled = false;
                submitButtonCarrito.textContent = 'Pagar Carrito ${{ number_format($total, 2) }}';
            }
        });
    @endif
</script>
@endpush