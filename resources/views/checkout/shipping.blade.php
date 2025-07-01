@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-6">
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

    <form action="{{ route('checkout.process_shipping') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Direcciones guardadas --}}
        @if ($userShippingAddresses->isNotEmpty())
            <div>
                <h2 class="text-xl font-semibold mb-2">Direcciones guardadas</h2>
                @foreach ($userShippingAddresses as $address)
                    <div class="mb-2 border p-4 rounded flex items-center gap-4">
                        <input type="radio" name="selected_address_id" value="{{ $address->id }}"
                               {{ $shippingAddress && $shippingAddress->id === $address->id ? 'checked' : '' }}>
                        <div>
                            <strong>{{ $address->name }}</strong> - {{ $address->phone }}<br>
                            {{ $address->street }} #{{ $address->number }}, {{ $address->district }}<br>
                            {{ $address->city }}, {{ $address->state }} - CP {{ $address->postal_code }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Opción para nueva dirección --}}
        <div>
            <label class="flex items-center gap-2 mt-4 font-semibold">
                <input type="radio" name="selected_address_id" value="new"
                    {{ old('selected_address_id') === 'new' ? 'checked' : '' }}>
                Capturar nueva dirección
            </label>
        </div>

        {{-- Formulario de nueva dirección --}}
        <fieldset id="new_address_form_fields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 border p-4 rounded mt-4 hidden">
            <div>
                <label class="block font-medium">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label class="block font-medium">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label class="block font-medium">Calle</label>
                <input type="text" name="street" value="{{ old('street') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label class="block font-medium">Número</label>
                <input type="text" name="number" value="{{ old('number') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label class="block font-medium">Número Interior</label>
                <input type="text" name="internal_number" value="{{ old('internal_number') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label class="block font-medium">Colonia / Barrio</label>
                <input type="text" name="district" value="{{ old('district') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label class="block font-medium">Ciudad</label>
                <input type="text" name="city" value="{{ old('city') }}" class="w-full input input-bordered">
            </div>
            
            <div>
                <label class="block font-medium">Estado</label>
                <select name="state" class="w-full input input-bordered">
                    <option value="">-- Selecciona --</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->abbreviation }}" {{ old('state') == $state->abbreviation ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-medium">Código Postal</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full input input-bordered">
            </div>
            <div>
                <label class="block font-medium">País</label>
                <input type="text" name="country" value="{{ old('country', 'MX') }}" class="w-full input input-bordered">
            </div>
            <div class="sm:col-span-2">
                <label class="block font-medium">Referencia (opcional)</label>
                <input type="text" name="reference" value="{{ old('reference') }}" class="w-full input input-bordered">
            </div>
            
        </fieldset>

        {{-- Botón de continuar --}}
        <div class="text-center">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                Continuar con el método de envío
            </button>
        </div>
    </form>
</div>

{{-- Script para mostrar u ocultar campos --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const radios = document.querySelectorAll('[name="selected_address_id"]');
    const formFields = document.getElementById('new_address_form_fields');

    function toggleFields() {
        const selected = document.querySelector('[name="selected_address_id"]:checked');
        if (selected && selected.value === 'new') {
            formFields.classList.remove('hidden');
        } else {
            formFields.classList.add('hidden');
        }
    }

    radios.forEach(r => r.addEventListener('change', toggleFields));
    toggleFields(); // ejecuta al inicio
});
</script>
@endsection
