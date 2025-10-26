@extends('checkout.layouts.checkout')

@section('title', 'Paso 2: Método de Envío - Checkout')

@section('content')
<div class="container mx-auto px-4 max-w-3xl">

    {{-- Barra de progreso del Checkout --}}
    @include('checkout.layouts._progressbar', ['step' => 2])

    <div class="bg-oaxaca-card-bg rounded-xl shadow-lg p-8 border border-oaxaca-accent border-opacity-20">
        <h1 class="text-3xl font-display text-oaxaca-primary mb-8 text-center">Selecciona tu Método de Envío</h1>

        {{-- Mensajes de error --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6">
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        
        <form action="{{ route('checkout.payment') }}" method="POST">
            @csrf

            {{-- Aquí se muestra la dirección a la que se cotizó el envío --}}
            <div class="mb-8 p-6 bg-oaxaca-bg-light rounded-lg border border-oaxaca-accent border-opacity-30">
                <h2 class="text-xl font-semibold mb-2 text-oaxaca-primary">Enviando a:</h2>
                <p>
                    <strong>{{ $shippingAddress->street }} #{{ $shippingAddress->number }}</strong><br>
                    {{ $shippingAddress->district }}, {{ $shippingAddress->city }}<br>
                    {{ $shippingAddress->state }} {{ $shippingAddress->postal_code }}
                </p>
            </div>

            {{-- Lista de opciones de envío --}}
            <div class="space-y-4">
                @forelse ($allShippingOptions as $option)
                    <label class="block cursor-pointer p-5 rounded-lg border border-oaxaca-accent border-opacity-30 shadow-sm transition-all duration-200 hover:bg-oaxaca-bg-light hover:border-oaxaca-primary">
                        <input type="radio" name="selected_quote_id" value="{{ $option['carrierId'] }}-{{ $option['serviceId'] }}"
                               class="form-radio h-5 w-5 text-oaxaca-primary focus:ring-oaxaca-primary mr-3"
                               required>
                        <span class="font-medium text-oaxaca-text-dark">
                            <span class="flex justify-between items-center">
                                <span class="text-lg">
                                    {{ $option['carrierDescription'] }} - {{ $option['serviceDescription'] }}
                                </span>
                                <span class="font-bold text-oaxaca-primary text-xl">
                                    ${{ number_format($option['totalPrice'], 2) }} MXN
                                </span>
                            </span>
                            <span class="block text-sm text-gray-600 mt-1">
                                Entrega estimada: {{ $option['deliveryEstimate'] }}
                            </span>
                        </span>
                    </label>
                @empty
                    <div class="text-center text-gray-500 py-10">
                        <p class="text-lg font-semibold">No se encontraron opciones de envío.</p>
                        <p>Por favor, revisa los datos de tu dirección e inténtalo de nuevo.</p>
                        <a href="{{ route('checkout.shipping') }}" class="text-oaxaca-primary underline mt-2 inline-block">Volver a la dirección de envío</a>
                    </div>
                @endforelse
            </div>

            {{-- Botón de continuar --}}
            <div class="flex justify-between mt-8">
                <a href="{{ route('checkout.shipping') }}" class="bg-gray-200 text-oaxaca-text-dark px-8 py-3 rounded-lg hover:bg-gray-300 transition-colors font-medium shadow-sm">
                    &larr; Volver a Dirección
                </a>
                <button type="submit" class="bg-oaxaca-primary text-white px-8 py-3 rounded-lg hover:bg-oaxaca-secondary transition-colors font-semibold shadow-md">
                    Continuar al Pago &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection