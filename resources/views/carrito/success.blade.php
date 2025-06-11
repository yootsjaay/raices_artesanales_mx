@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <div class="display-1 text-success mb-4">✓</div>
    <h1 class="mb-4">¡Pago Exitoso!</h1>
    <p class="lead mb-4">Tu orden ha sido procesada correctamente</p>
    <p class="mb-4">ID de transacción: <code>{{ $payment_id }}</code></p>
    
    <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-home me-2"></i> Volver al inicio
        </a>
        <a href="{{ route('mis-ordenes') }}" class="btn btn-outline-primary">
            <i class="fas fa-list me-2"></i> Ver mis órdenes
        </a>
    </div>
</div>
@endsection