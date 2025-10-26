@extends('checkout.layouts.checkout')

@section('title', '¡Pago Exitoso!')

@section('content')
<div class="container mx-auto px-4 max-w-2xl text-center py-12">
    <div class="bg-white p-8 rounded-lg shadow-lg border border-green-200">
        <h1 class="text-4xl font-bold text-green-600 mb-4">¡Gracias por tu compra!</h1>
        <p class="text-lg text-gray-700 mb-6">Tu pago ha sido procesado exitosamente y tu pedido está en camino.</p>
        
        <div class="bg-gray-100 p-6 rounded-lg mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Detalles de tu Pedido</h2>
            <p><strong>Número de Orden:</strong> {{ $order->id }}</p>
            <p><strong>Estado de la Orden:</strong> {{ $order->status }}</p>
            @if($order->shippingLabel)
                <p><strong>Número de Rastreo:</strong> {{ $order->shippingLabel->tracking_number }}</p>
                <p><strong>Etiqueta de Envío:</strong> <a href="{{ $order->shippingLabel->label_url }}" target="_blank" class="text-blue-500 underline">Descargar aquí</a></p>
            @endif
        </div>

        <a href="{{ route('home') }}" class="inline-block bg-oaxaca-primary text-white font-semibold py-3 px-8 rounded-lg transition-colors hover:bg-oaxaca-secondary">
            Regresar a la Tienda
        </a>
    </div>
</div>
@endsection