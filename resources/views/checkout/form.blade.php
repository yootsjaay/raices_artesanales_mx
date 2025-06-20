@extends('layouts.public')

@section('content')
<div class="container mx-auto py-10 px-4 max-w-3xl">
    <h2 class="text-4xl font-display text-oaxaca-primary font-bold mb-8 text-center animate-fade-in">
        Dirección de Envío
    </h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded mb-6">
            <strong class="font-bold">¡Ups!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout') }}" class="bg-oaxaca-card-bg p-8 rounded-xl shadow-md border border-oaxaca-primary border-opacity-10 space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-oaxaca-text-dark">Nombre completo</label>
            <input type="text" name="name" id="name" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-oaxaca-text-dark">Correo electrónico</label>
            <input type="email" name="email" id="email" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-oaxaca-text-dark">Teléfono</label>
            <input type="text" name="phone" id="phone" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="street" class="block text-sm font-medium text-oaxaca-text-dark">Calle</label>
            <input type="text" name="street" id="street" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="number" class="block text-sm font-medium text-oaxaca-text-dark">Número</label>
            <input type="text" name="number" id="number" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="district" class="block text-sm font-medium text-oaxaca-text-dark">Colonia</label>
            <input type="text" name="district" id="district" class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="city" class="block text-sm font-medium text-oaxaca-text-dark">Ciudad</label>
            <input type="text" name="city" id="city" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="state" class="block text-sm font-medium text-oaxaca-text-dark">Estado</label>
            <input type="text" name="state" id="state" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="postal_code" class="block text-sm font-medium text-oaxaca-text-dark">Código Postal</label>
            <input type="text" name="postal_code" id="postal_code" required class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary">
        </div>

        <div>
            <label for="reference" class="block text-sm font-medium text-oaxaca-text-dark">Referencia (opcional)</label>
            <textarea name="reference" id="reference" rows="3" class="mt-1 w-full p-3 border border-oaxaca-primary rounded-md shadow-sm focus:ring-oaxaca-secondary focus:border-oaxaca-secondary"></textarea>
        </div>

        <div class="text-center pt-4">
            <button type="submit" class="bg-oaxaca-primary text-white px-6 py-3 rounded-lg hover:bg-oaxaca-secondary transition-colors duration-300 font-semibold shadow-lg transform hover:scale-105">
                Generar Envío
            </button>
        </div>
    </form>
</div>
@endsection
