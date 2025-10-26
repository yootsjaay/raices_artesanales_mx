@extends('checkout.layouts.checkout')

@section('title', 'Pago Pendiente')

@section('content')
<div class="container mx-auto px-4 max-w-2xl text-center py-12">
    <div class="bg-white p-8 rounded-lg shadow-lg border border-yellow-200">
        <h1 class="text-4xl font-bold text-yellow-600 mb-4">Tu Pago está Pendiente</h1>
        <p class="text-lg text-gray-700 mb-6">Estamos procesando tu pago. Esto puede tomar algunos minutos. Recibirás un correo electrónico cuando el estado de tu pedido cambie.</p>
        
        <div class="bg-gray-100 p-6 rounded-lg mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Información del Pedido</h2>
            <p><strong>Número de Orden:</strong> {{ $order->id }}</p>
            <p><strong>Estado del Pago:</strong> Pendiente de Aprobación</p>
        </div>

        <a href="{{ route('home') }}" class="inline-block bg-oaxaca-primary text-white font-semibold py-3 px-8 rounded-lg transition-colors hover:bg-oaxaca-secondary">
            Ir a la página principal
        </a>
    </div>
</div>
@endsection