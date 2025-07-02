@extends('comprador.layouts.public')

@section('title', 'Carrito de Compras - Raíces Artesanales MX')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Tu Carrito</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if(count($carrito) > 0)
        <table class="w-full text-left border-collapse mb-6">
            <thead>
                <tr>
                    <th class="border p-2">Imagen</th>
                    <th class="border p-2">Producto</th>
                    <th class="border p-2">Precio</th>
                    <th class="border p-2">Cantidad</th>
                    <th class="border p-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($carrito as $id => $producto)
                    @php $subtotal = $producto['precio'] * $producto['cantidad']; $total += $subtotal; @endphp
                    <tr>
                        <td class="border p-2"><img src="{{ asset('storage/' . $producto['imagen']) }}" alt="{{ $producto['nombre'] }}" class="w-20 h-20 object-cover"></td>
                        <td class="border p-2">{{ $producto['nombre'] }}</td>
                        <td class="border p-2">${{ number_format($producto['precio'], 2) }} MXN</td>
                        <td class="border p-2">{{ $producto['cantidad'] }}</td>
                        <td class="border p-2">${{ number_format($subtotal, 2) }} MXN</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-right font-bold p-2">Total:</td>
                    <td class="border p-2 font-bold">${{ number_format($total, 2) }} MXN</td>
                </tr>
            </tbody>
        </table>
    @else
        <p>Tu carrito está vacío.</p>
    @endif

    <a href="{{ route('artesanias.index') }}" class="bg-oaxaca-button-mustard text-oaxaca-text-dark-gray py-2 px-4 rounded hover:bg-oaxaca-button-mustard-hover">Seguir Comprando</a>
</div>
@endsection
