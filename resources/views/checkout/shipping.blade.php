@extends('layouts.public')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md mt-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Tu Dirección de Envío</h1>

    @if (session('error'))
        <div class="mb-4 text-red-600 font-semibold">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.process_shipping') }}" method="POST" class="space-y-4">
        @csrf

        <h2 class="text-xl font-semibold mb-4">Ingresa tu Nueva Dirección de Envío:</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="company" class="block font-medium">Empresa (opcional):</label>
                <input type="text" id="company" name="company" value="{{ old('company') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label for="name" class="block font-medium">Nombre Completo:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="email" class="block font-medium">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label for="phone" class="block font-medium">Teléfono:</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="street" class="block font-medium">Calle:</label>
                <input type="text" id="street" name="street" value="{{ old('street') }}" placeholder="Ej. Av. Reforma" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="number" class="block font-medium">Número Exterior:</label>
                <input type="text" id="number" name="number" value="{{ old('number') }}" placeholder="Ej. 123" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="internal_number" class="block font-medium">Número Interior (opcional):</label>
                <input type="text" id="internal_number" name="internal_number" value="{{ old('internal_number') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label for="district" class="block font-medium">Colonia / Barrio:</label>
                <input type="text" id="district" name="district" value="{{ old('district') }}" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="city" class="block font-medium">Ciudad:</label>
                <input type="text" id="city" name="city" value="{{ old('city') }}" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="state" class="block font-medium">Estado:</label>
                <input type="text" id="state" name="state" value="{{ old('state') }}" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="postal_code" class="block font-medium">Código Postal:</label>
                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="country" class="block font-medium">País:</label>
                <input type="text" id="country" name="country" value="{{ old('country', 'MX') }}" required class="w-full input input-bordered">
            </div>
            <div>
                <label for="phone_code" class="block font-medium">Código Tel. País (opcional):</label>
                <input type="text" id="phone_code" name="phone_code" value="{{ old('phone_code', 'MX') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label for="category" class="block font-medium">Categoría (opcional, 1-3):</label>
                <input type="number" id="category" name="category" value="{{ old('category', 1) }}" min="1" max="3" class="w-full input input-bordered">
            </div>
            <div class="sm:col-span-2">
                <label for="identification_number" class="block font-medium">Número de Identificación (RFC/CURP, opcional):</label>
                <input type="text" id="identification_number" name="identification_number" value="{{ old('identification_number') }}" class="w-full input input-bordered">
            </div>
            <div class="sm:col-span-2">
                <label for="reference" class="block font-medium">Referencia (ej. "Casa con portón rojo"):</label>
                <input type="text" id="reference" name="reference" value="{{ old('reference') }}" class="w-full input input-bordered">
            </div>
        </div>

        <div class="mt-6 text-center">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
        Continuar
    </button>
</div>

    </form>
</div>
@endsection
