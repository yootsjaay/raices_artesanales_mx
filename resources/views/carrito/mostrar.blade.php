@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tu Carrito</h1>

    @if(count($carrito) > 0)
        <table class="w-full border border-gray-300 rounded shadow">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Producto</th>
                    <th class="p-3">Cantidad</th>
                    <th class="p-3">Precio</th>
                    <th class="p-3">Subtotal</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($carrito as $id => $item)
                    @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
                    <tr>
                        <td class="p-3">{{ $item['nombre'] }}</td>
                        <td class="p-3">{{ $item['cantidad'] }}</td>
                        <td class="p-3">${{ number_format($item['precio'], 2) }}</td>
                        <td class="p-3">${{ number_format($subtotal, 2) }}</td>
                        <td class="p-3">
                            <form action="{{ route('carrito.eliminar', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Quitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-gray-100 font-bold">
                    <td colspan="3" class="p-3 text-right">Total:</td>
                    <td class="p-3">${{ number_format($total, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6">
            <a href="{{ route('checkout.iniciar') }}" class="bg-green-500 text-white px-6 py-3 rounded hover:bg-green-600">
                Proceder al Pago
            </a>
        </div>

    @else
        <p>Tu carrito está vacío.</p>
    @endif
</div>
@endsection
