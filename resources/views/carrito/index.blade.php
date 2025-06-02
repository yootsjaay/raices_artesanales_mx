@extends('layouts.public')

@section('content')
<div class="container mx-auto py-6 px-4 max-w-7xl">
    <h1 class="text-4xl font-extrabold text-oaxaca-title-pink mb-8 text-center">Tu Carrito de Compras</h1>

    {{-- Mensajes de sesión --}}
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

    @if ($cartItems->isEmpty())
        {{-- Carrito vacío --}}
        <div class="bg-white p-10 rounded-lg shadow-lg text-center">
            <i data-lucide="shopping-cart-x" class="mx-auto mb-6 w-20 h-20 text-gray-400"></i>
            <p class="text-xl text-gray-700 mb-4">Tu carrito está vacío. ¡Explora nuestras artesanías!</p>
            <a href="{{ route('artesanias.index') }}" class="inline-block bg-oaxaca-navbar-blue hover:bg-oaxaca-navbar-orange text-white font-semibold px-6 py-3 rounded-lg transition duration-300">
                <i data-lucide="shopping-bag" class="inline w-5 h-5 mr-2"></i> Ver Catálogo
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Detalles del carrito --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-6 py-3 relative"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="h-20 w-20 rounded-lg object-cover" src="{{ asset('storage/' . $item->artesania->imagen_principal) }}" alt="{{ $item->artesania->nombre }}">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->artesania->nombre }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        ${{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                       <form action="{{ route('carrito.actualizar') }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PUT') <!-- Esto es clave -->
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="number"
                                            name="cantidad"
                                            value="{{ $item->quantity }}"
                                            min="0"
                                            class="w-20 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-oaxaca-navbar-blue focus:border-oaxaca-navbar-blue sm:text-sm"
                                            onchange="this.form.submit()">
                                    </form>

                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                       <form action="{{ route('carrito.remover') }}" method="POST">
                                        @csrf
                                        @method('DELETE') <!-- Simula el método DELETE -->
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
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
                        <button type="submit" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-300 text-base font-semibold shadow-md">
                            Vaciar Carrito
                        </button>
                    </form>
                </div>
            </div>

            {{-- Resumen del pedido --}}
            <div class="bg-white shadow rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-semibold mb-4">Resumen del Pedido</h2>
                    <ul class="divide-y divide-gray-200 mb-6">
                        @foreach($cartItems as $item)
                        <li class="py-3 flex items-center gap-4">
                            @if($item->artesania->imagen)
                                <img src="{{ asset('storage/' . $item->artesania->imagen) }}" alt="{{ $item->artesania->nombre }}" class="w-14 h-14 object-cover rounded">
                            @endif
                            <div class="flex-grow">
                                <p class="font-semibold">{{ $item->artesania->nombre }}</p>
                                <p class="text-gray-600">Cantidad: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-semibold">{{ number_format($item->quantity * $item->price, 2) }} MXN</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-gray-300 pt-4">
                        <div class="flex justify-between font-semibold text-lg mb-4">
                            <span>Total:</span>
                            <span>{{ number_format($total, 2) }} MXN</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                

                    <a href="#" {{-- Reemplaza con tu ruta de pago --}}
                       class="w-full bg-oaxaca-navbar-blue hover:bg-oaxaca-navbar-orange text-white px-4 py-2 rounded flex justify-center items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5"></i> Proceder al Pago
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
