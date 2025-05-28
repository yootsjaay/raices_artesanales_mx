@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Tu carrito</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (empty($carrito))
        <p>Tu carrito está vacío.</p>
    @else
        <table class="min-w-full bg-white shadow rounded">
            <thead>
                <tr>
                    <th class="py-2 px-4">Imagen</th>
                    <th class="py-2 px-4">Nombre</th>
                    <th class="py-2 px-4">Cantidad</th>
                    <th class="py-2 px-4">Precio</th>
                    <th class="py-2 px-4">Subtotal</th>
                    <th class="py-2 px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carrito as $id => $item)
                    <tr class="border-t">
                        <td class="py-2 px-4">
                            <img src="{{ asset('storage/artesanias/' . $item['imagen']) }}" alt="" class="w-16 h-16 object-cover">
                        </td>
                        <td class="py-2 px-4">{{ $item['nombre'] }}</td>
                        <td class="py-2 px-4">{{ $item['cantidad'] }}</td>
                        <td class="py-2 px-4">${{ number_format($item['precio'], 2) }}</td>
                        <td class="py-2 px-4">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                        <td class="py-2 px-4">
                            <form action="{{ route('carrito.remover') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            <h3 class="text-xl font-semibold">Total: ${{ number_format($total, 2) }}</h3>

            <form action="{{ route('carrito.vaciar') }}" method="POST" class="mt-4 inline-block">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Vaciar carrito
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
