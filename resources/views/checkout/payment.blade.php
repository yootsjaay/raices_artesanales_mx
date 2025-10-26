@extends('checkout.layouts.checkout')

@section('title', 'Paso 3: Pago - Checkout')

@section('content')
<div class="container mx-auto px-4 max-w-3xl">

    {{-- Barra de progreso --}}
    @include('checkout.layouts._progressbar', ['step' => 3])

    <div class="bg-oaxaca-card-bg rounded-xl shadow-lg p-8 border border-oaxaca-accent border-opacity-20">
        <h1 class="text-3xl font-display text-oaxaca-primary mb-8 text-center">Resumen y Pago</h1>

        {{-- Resumen de la orden --}}
        <div class="mb-8 p-6 bg-oaxaca-bg-light rounded-lg border border-oaxaca-accent border-opacity-30">
            <h2 class="text-xl font-semibold mb-4 text-oaxaca-primary">Detalle de tu orden:</h2>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-oaxaca-text-dark">
                    <span>Productos</span>
                    <span>${{ number_format($cart_total, 2) }} MXN</span>
                </div>
                <div class="flex justify-between items-center text-oaxaca-text-dark">
                    <span>Envío</span>
                    <span>${{ number_format($shipping_cost, 2) }} MXN</span>
                </div>
                <hr class="border-oaxaca-accent border-opacity-30 my-2">
                <div class="flex justify-between items-center text-2xl font-bold text-oaxaca-primary">
                    <span>Total</span>
                    <span>${{ number_format($final_total, 2) }} MXN</span>
                </div>
            </div>
        </div>

        {{-- Contenedor del botón de Mercado Pago --}}
        <div class="flex flex-col items-center">
            <div id="mercado-pago-button"></div>
        </div>
    </div>
</div>

{{-- Script de Mercado Pago --}}
<script src="https://api.mercadopago.com/checkout/preferences"></script>

<script>
    const mp = new MercadoPago("{{ config('mercadoservice.mercadopago.public_key') }}", {
        locale: 'es-MX'
    });

    mp.checkout({
        preference: {
            id: '{{ $preference->id }}'
        },
        render: {
            container: '#mercado-pago-button',
            label: 'Pagar con Mercado Pago',
        }
    });
</script>
@endsection