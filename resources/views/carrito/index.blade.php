@extends('comprador.layouts.public')

@section('content')
@role('comprador')

<div class="container mx-auto py-8 px-4 max-w-7xl"> {{-- Padding y ancho máximo --}}
    <h1 class="text-5xl md:text-6xl font-display font-bold text-oaxaca-primary mb-10 text-center animate-fade-in">Tu Carrito de Compras</h1> {{-- Título con font-display y color primario --}}

    {{-- Mensajes de sesión --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 font-sans" role="alert"> {{-- Fuente sans para mensajes --}}
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 font-sans" role="alert"> {{-- Fuente sans para mensajes --}}
            <strong class="font-bold">¡Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($cartItems->isEmpty())
        {{-- Carrito vacío --}}
        <div class="bg-oaxaca-card-bg p-10 rounded-xl shadow-lg text-center border border-oaxaca-primary border-opacity-10"> {{-- Fondo de tarjeta, sombra y borde --}}
            <svg class="mx-auto mb-6 w-24 h-24 text-oaxaca-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> {{-- Icono de carrito más grande y con color de acento --}}
            <p class="text-2xl text-oaxaca-text-dark mb-6 font-sans">Tu carrito está vacío. ¡Explora nuestras auténticas artesanías!</p> {{-- Texto y fuente --}}
            <a href="{{ route('artesanias.index') }}" class="inline-block bg-oaxaca-primary hover:bg-oaxaca-secondary text-oaxaca-text-light font-semibold px-8 py-4 rounded-lg transition-colors duration-300 shadow-md transform hover:scale-105"> {{-- Botón primario --}}
                <svg class="inline w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Ver Catálogo
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Detalles del carrito --}}
            <div class="lg:col-span-2 bg-oaxaca-card-bg p-6 rounded-xl shadow-lg border border-oaxaca-primary border-opacity-10"> {{-- Fondo de tarjeta, sombra y borde --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-oaxaca-primary divide-opacity-20"> {{-- Divisores de tabla con color de marca --}}
                        <thead class="bg-oaxaca-primary bg-opacity-5"> {{-- Fondo de cabecera de tabla --}}
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-oaxaca-primary uppercase tracking-wider">Producto</th> {{-- Texto de cabecera --}}
                                <th class="px-6 py-3 text-left text-sm font-semibold text-oaxaca-primary uppercase tracking-wider">Precio Unitario</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-oaxaca-primary uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-oaxaca-primary uppercase tracking-wider">Subtotal</th>
                                <th class="px-6 py-3 relative"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-oaxaca-card-bg divide-y divide-oaxaca-primary divide-opacity-10"> {{-- Fondo y divisores de cuerpo de tabla --}}
                            @foreach ($cartItems as $item)
                                @php
                                    // Determinar la imagen a mostrar (variante o principal)
                                    $itemImage = null;
                                    if ($item->artesania_variant && $item->artesania_variant->image) {
                                        $itemImage = asset('storage/' . $item->artesania_variant->image);
                                    } elseif ($item->artesania->imagen_principal) {
                                        $itemImage = asset('storage/' . $item->artesania->imagen_principal);
                                    } else {
                                        $itemImage = asset('storage/images/artesanias/placeholder-alebrije.jpg');
                                    }

                                    // Determinar el nombre a mostrar (variante o principal)
                                    $itemName = $item->artesania->nombre;
                                    $variantDetails = '';
                                    if ($item->artesania_variant) {
                                        $itemName = $item->artesania_variant->variant_name ?: $item->artesania->nombre; // Usar nombre de variante si existe
                                        $details = [];
                                        if ($item->artesania_variant->size) $details[] = 'Talla: ' . $item->artesania_variant->size;
                                        if ($item->artesania_variant->color) $details[] = 'Color: ' . $item->artesania_variant->color;
                                        if ($item->artesania_variant->material_variant) $details[] = 'Material: ' . $item->artesania_variant->material_variant;
                                        if (!empty($details)) {
                                            $variantDetails = ' (' . implode(', ', $details) . ')';
                                        }
                                    }

                                    // Determinar el stock disponible para el input de cantidad
                                    $availableStock = $item->artesania_variant ? $item->artesania_variant->stock : $item->artesania->stock;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="h-24 w-24 rounded-lg object-cover border border-oaxaca-accent border-opacity-30" src="{{ $itemImage }}" alt="{{ $itemName }}"> {{-- Borde de imagen --}}
                                            <div class="ml-4">
                                                {{-- Enlace a la página del producto, incluyendo la variante si aplica --}}
                                                <a href="{{ route('artesanias.show', ['slug' => $item->artesania->slug, 'variant' => $item->artesania_variant->id ?? null]) }}" class="text-base font-semibold text-oaxaca-primary hover:text-oaxaca-secondary transition-colors">
                                                    {{ $itemName }}
                                                </a>
                                                @if($variantDetails)
                                                    <p class="text-sm text-gray-600">{{ $variantDetails }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-base text-oaxaca-text-dark font-medium">
                                        ${{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                       <form action="{{ route('carrito.actualizar') }}" method="POST" class="flex items-center">
                                        @csrf
                                        {{-- Usamos PUT para actualizar semánticamente, aunque el controlador puede aceptar POST --}}
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="number"
                                            name="cantidad"
                                            value="{{ $item->quantity }}"
                                            min="0"
                                            max="{{ $availableStock }}" {{-- Stock máximo basado en la variante o artesanía --}}
                                            class="w-24 p-2 border border-oaxaca-primary border-opacity-30 rounded-md shadow-sm focus:ring-oaxaca-tertiary focus:border-oaxaca-tertiary text-oaxaca-text-dark text-base" {{-- Estilo de input --}}
                                            onchange="this.form.submit()">
                                    </form>
                                    </td>
                                    <td class="px-6 py-4 text-right text-base font-medium text-oaxaca-text-dark">
                                        ${{ number_format($item->quantity * $item->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                       <form action="{{ route('carrito.remover') }}" method="POST">
                                        @csrf
                                        {{-- Usamos DELETE para remover semánticamente, aunque el controlador puede aceptar POST --}}
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit" class="text-oaxaca-secondary hover:text-red-700 transition-colors">Eliminar</button> {{-- Color del botón de eliminar --}}
                                    </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Vaciar carrito --}}
                <div class="mt-8 text-right">
                    <form action="{{ route('carrito.vaciar') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-oaxaca-accent bg-opacity-10 text-oaxaca-accent px-6 py-3 rounded-lg hover:bg-oaxaca-accent hover:text-white transition-colors duration-300 text-base font-semibold shadow-md"> {{-- Botón de vaciar carrito --}}
                            Vaciar Carrito
                        </button>
                    </form>
                </div>
            </div>

            {{-- Resumen del pedido --}}
            <div class="bg-oaxaca-card-bg shadow-lg rounded-xl p-6 flex flex-col justify-between border border-oaxaca-primary border-opacity-10"> {{-- Fondo de tarjeta, sombra y borde --}}
                <div>
                    <h2 class="text-2xl font-display font-semibold text-oaxaca-primary mb-5">Resumen del Pedido</h2> {{-- Título del resumen --}}
                    <ul class="divide-y divide-oaxaca-primary divide-opacity-10 mb-6"> {{-- Divisores de lista --}}
                        @foreach($cartItems as $item)
                        @php
                            // Determinar el nombre a mostrar (variante o principal) para el resumen
                            $summaryItemName = $item->artesania->nombre;
                            $summaryVariantDetails = '';
                            if ($item->artesania_variant) {
                                $summaryItemName = $item->artesania_variant->variant_name ?: $item->artesania->nombre;
                                $summaryDetails = [];
                                if ($item->artesania_variant->size) $summaryDetails[] = 'Talla: ' . $item->artesania_variant->size;
                                if ($item->artesania_variant->color) $summaryDetails[] = 'Color: ' . $item->artesania_variant->color;
                                if ($item->artesania_variant->material_variant) $summaryDetails[] = 'Material: ' . $item->artesania_variant->material_variant;
                                if (!empty($summaryDetails)) {
                                    $summaryVariantDetails = ' (' . implode(', ', $summaryDetails) . ')';
                                }
                            }
                        @endphp
                        <li class="py-3 flex items-center gap-4">
                            <div class="flex-grow">
                                <p class="font-semibold text-oaxaca-text-dark">{{ $summaryItemName }}</p>
                                @if($summaryVariantDetails)
                                    <p class="text-oaxaca-text-dark text-sm">{{ $summaryVariantDetails }}</p>
                                @endif
                                <p class="text-oaxaca-text-dark text-sm">Cantidad: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-bold text-oaxaca-primary">${{ number_format($item->quantity * $item->price, 2) }} MXN</span> {{-- Color del subtotal --}}
                        </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-oaxaca-primary border-opacity-20 pt-4 mt-4"> {{-- Divisor de total --}}
                        <div class="flex justify-between font-bold text-xl mb-4 text-oaxaca-primary"> {{-- Color del total --}}
                            <span>Total:</span>
                            <span>${{ number_format($total, 2) }} MXN</span>
                        </div>
                    </div>
                </div>

               <div class="flex flex-col gap-3">
    <a href="{{ route('checkout.shipping') }}"
       class="w-full bg-oaxaca-tertiary hover:bg-oaxaca-secondary text-oaxaca-primary hover:text-white font-semibold px-6 py-3 rounded-lg flex justify-center items-center gap-2 transition-colors duration-300 shadow-md transform hover:scale-105">
       <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 4h16c1.11 0 2 .89 2 2v12c0 1.11-.89 2-2 2H4c-1.11 0-2-.89-2-2V6c0-1.11.89-2 2-2zm0 2v2h16V6H4zm0 12h16v-6H4v6zm2-3c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"></path></svg>
       Proceder al Pago
    </a>
</div>
            </div>
        </div>
    @endif
</div>
@endrole

@endsection
