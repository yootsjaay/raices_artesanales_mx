@extends('comprador.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 bg-white rounded-xl shadow-lg p-8 mt-8 border border-oaxaca-primary border-opacity-10">
    <h1 class="text-3xl font-bold text-oaxaca-primary mb-6">Tu Carrito de Compras</h1>

    @if($cartItems->count() > 0)
        <div class="overflow-x-auto"> {{-- Para manejar tablas en pantallas pequeñas --}}
            <table class="w-full border-collapse border border-oaxaca-primary border-opacity-30 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-oaxaca-bg-light text-oaxaca-primary text-left">
                        <th class="p-4">Producto</th>
                        <th class="p-4 text-center">Cantidad</th>
                        <th class="p-4 text-right">Precio Unitario</th>
                        <th class="p-4 text-right">Subtotal</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr class="border-b border-oaxaca-primary border-opacity-10 last:border-b-0">
                            <td class="p-4 flex items-center">
                                @php
                                    // Determinar la imagen a mostrar (variante o principal)
                                    $itemImage = null;
                                    // Primero, intentar obtener la imagen principal de la VARIANTE
                                    if ($item->artesania_variant && $item->artesania_variant->imagenPrincipal) {
                                        $itemImage = asset('storage/' . $item->artesania_variant->imagenPrincipal->ruta);
                                    }
                                    // Si no hay imagen de variante, intentar obtener la imagen principal de la ARTESANÍA
                                    elseif ($item->artesania->imagenPrincipal) {
                                        $itemImage = asset('storage/' . $item->artesania->imagenPrincipal->ruta);
                                    }
                                    // Si no hay ninguna imagen, usar un placeholder
                                    else {
                                        $itemImage = asset('storage/images/artesanias/placeholder-alebrije.jpg');
                                    }

                                    // Determinar el nombre a mostrar (variante o principal)
                                    $itemName = $item->artesania->nombre;
                                    $variantDetails = '';
                                    if ($item->artesania_variant) {
                                        $itemName = $item->artesania_variant->variant_name ?: $item->artesania->nombre;
                                        $details = [];
                                        if ($item->artesania_variant->size) $details[] = 'Talla: ' . $item->artesania_variant->size;
                                        if ($item->artesania_variant->color) $details[] = 'Color: ' . $item->artesania_variant->color;
                                        if ($item->artesania_variant->material_variant) $details[] = 'Material: ' . $item->artesania_variant->material_variant;
                                        if (!empty($details)) {
                                            $variantDetails = ' (' . implode(', ', $details) . ')';
                                        }
                                    }
                                @endphp
                                <img src="{{ $itemImage }}" alt="{{ $itemName }}" class="w-16 h-16 object-cover rounded-md mr-4 border border-oaxaca-accent border-opacity-20">
                                <div>
                                    <a href="{{ route('artesanias.show', $item->artesania->slug) }}" class="font-semibold text-oaxaca-primary hover:text-oaxaca-secondary transition-colors">
                                        {{ $itemName }}
                                    </a>
                                    @if($variantDetails)
                                        <p class="text-sm text-gray-600">{{ $variantDetails }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('carrito.actualizar') }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="number" name="cantidad" value="{{ $item->quantity }}" min="0"
                                        class="w-20 p-2 border border-oaxaca-primary border-opacity-30 rounded-md text-center text-oaxaca-text-dark focus:outline-none focus:ring-1 focus:ring-oaxaca-tertiary">
                                    <button type="submit" class="bg-oaxaca-tertiary text-oaxaca-primary px-3 py-1 rounded-md hover:bg-oaxaca-secondary hover:text-white transition-colors">
                                        Actualizar
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-right text-oaxaca-text-dark">${{ number_format($item->price, 2) }}</td>
                            <td class="p-4 text-right text-oaxaca-text-dark font-semibold">${{ number_format($item->quantity * $item->price, 2) }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('carrito.remover') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-colors">
                                        Quitar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-oaxaca-bg-light font-bold text-oaxaca-primary">
                        <td colspan="3" class="p-4 text-right">Total del Carrito:</td>
                        <td class="p-4 text-right">${{ number_format($total, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6">
            <form action="{{ route('carrito.vaciar') }}" method="POST">
                @csrf
                <button type="submit" class="bg-gray-300 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-400 transition-colors shadow-sm">
                    Vaciar Carrito
                </button>
            </form>

            <a href="{{ route('checkout') }}" class="bg-oaxaca-primary text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                Finalizar Compra
            </a>
        </div>

    @else
        <div class="text-center py-10">
            <p class="text-oaxaca-text-dark text-lg mb-4">Tu carrito está vacío. ¡Explora nuestras artesanías!</p>
            <a href="{{ route('artesanias.index') }}" class="bg-oaxaca-accent text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                Ver Artesanías
            </a>
        </div>
    @endif
</div>
@endsection