@extends('checkout.layouts.checkout')

@section('title', 'Pago Fallido')

@section('content')
<div class="container mx-auto px-4 max-w-2xl text-center py-12">
    <div class="bg-white p-8 rounded-lg shadow-lg border border-red-200">
        <h1 class="text-4xl font-bold text-red-600 mb-4">Hubo un Problema con tu Pago</h1>
        <p class="text-lg text-gray-700 mb-6">Lo sentimos, tu pago no pudo ser procesado.</p>
        
        <div class="bg-red-50 p-6 rounded-lg mb-6 border border-red-200">
            <h2 class="text-xl font-semibold text-red-800 mb-2">Detalles del Error</h2>
            <p>{{ $errorMessage }}</p>
        </div>
        
        <div class="space-y-4">
            <a href="{{ route('checkout.shipping') }}" class="inline-block bg-gray-200 text-oaxaca-text-dark font-semibold py-3 px-8 rounded-lg transition-colors hover:bg-gray-300">
                Intentar de Nuevo
            </a>
            <a href="" class="inline-block bg-oaxaca-primary text-white font-semibold py-3 px-8 rounded-lg transition-colors hover:bg-oaxaca-secondary">
                Contactar a Soporte
            </a>
        </div>
    </div>
</div>
@endsection