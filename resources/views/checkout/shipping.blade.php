@extends('checkout.layouts.checkout') {{-- Asegúrate de que este extiende el layout de checkout minimalista --}}

@section('title', 'Paso 1: Dirección de Envío - Checkout')

@section('content')
<div class="container mx-auto px-4 max-w-3xl">
    {{-- Barra de progreso del Checkout --}}
    <div class="flex justify-between items-center mb-10 text-sm font-medium text-gray-500">
        <div class="text-oaxaca-primary flex flex-col items-center">
            <span class="w-8 h-8 rounded-full bg-oaxaca-primary text-white flex items-center justify-center mb-1 font-bold">1</span>
            Dirección
        </div>
        <div class="flex-1 h-0.5 bg-oaxaca-primary mx-2"></div> {{-- Línea de progreso activa --}}
        <div class="flex flex-col items-center">
            <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center mb-1 font-bold">2</span>
            Envío
        </div>
        <div class="flex-1 h-0.5 bg-gray-300 mx-2"></div>
        <div class="flex flex-col items-center">
            <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center mb-1 font-bold">3</span>
            Pago
        </div>
        <div class="flex-1 h-0.5 bg-gray-300 mx-2"></div>
        <div class="flex flex-col items-center">
            <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center mb-1 font-bold">4</span>
            Revisar
        </div>
    </div>

    <div class="bg-oaxaca-card-bg rounded-xl shadow-lg p-8 border border-oaxaca-accent border-opacity-20">
        <h1 class="text-3xl font-display text-oaxaca-primary mb-8 text-center">Tu Dirección de Envío</h1>

        {{-- Mensajes de Sesión y Errores --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">¡Atención!</strong>
                <ul class="mt-2 list-disc list-inside">
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
                    <h2 class="text-xl font-semibold text-oaxaca-primary mb-4">Selecciona una dirección guardada:</h2>
                    <div class="space-y-4">
                        @foreach ($userShippingAddresses as $address)
                            <label class="block cursor-pointer bg-oaxaca-bg-light p-5 rounded-lg border border-oaxaca-accent border-opacity-30 shadow-sm hover:border-oaxaca-primary transition-all duration-200">
                                <input type="radio" name="selected_address_id" value="{{ $address->id }}"
                                       class="form-radio h-5 w-5 text-oaxaca-primary focus:ring-oaxaca-primary mr-3"
                                       {{ ($shippingAddress && $shippingAddress->id === $address->id) || old('selected_address_id') == $address->id ? 'checked' : '' }}>
                                <span class="font-medium text-oaxaca-text-dark">
                                    <strong>{{ $address->name }}</strong> - {{ $address->phone }}<br>
                                    {{ $address->street }} #{{ $address->number }}{{ $address->internal_number ? ' Int. ' . $address->internal_number : '' }}, {{ $address->district }}<br>
                                    {{ $address->city }}, {{ $address->state }} - CP {{ $address->postal_code }}<br>
                                    @if ($address->reference)
                                        <span class="text-sm text-gray-600">Referencia: {{ $address->reference }}</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="border-t border-oaxaca-accent border-opacity-20 pt-6 mt-6"></div> {{-- Separador --}}
            @endif

            {{-- Opción para nueva dirección --}}
            <div>
                <label class="flex items-center gap-3 font-semibold text-oaxaca-primary text-lg">
                    <input type="radio" name="selected_address_id" value="new"
                        class="form-radio h-5 w-5 text-oaxaca-primary focus:ring-oaxaca-primary"
                        {{ old('selected_address_id') === 'new' || $userShippingAddresses->isEmpty() ? 'checked' : '' }}> {{-- Si no hay direcciones guardadas, selecciona "nueva" por defecto --}}
                    Capturar una nueva dirección
                </label>
            </div>

            {{-- Formulario de nueva dirección --}}
            <fieldset id="new_address_form_fields" class="grid grid-cols-1 sm:grid-cols-2 gap-6 border border-oaxaca-accent border-opacity-30 p-6 rounded-lg bg-oaxaca-bg-light mt-6">
                <div>
                    <label for="name" class="block text-oaxaca-text-dark font-medium mb-2">Nombre Completo <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div>
                    <label for="phone" class="block text-oaxaca-text-dark font-medium mb-2">Teléfono <span class="text-red-500">*</span></label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div>
                    <label for="street" class="block text-oaxaca-text-dark font-medium mb-2">Calle <span class="text-red-500">*</span></label>
                    <input type="text" id="street" name="street" value="{{ old('street') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div>
                    <label for="number" class="block text-oaxaca-text-dark font-medium mb-2">Número Exterior <span class="text-red-500">*</span></label>
                    <input type="text" id="number" name="number" value="{{ old('number') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div>
                    <label for="internal_number" class="block text-oaxaca-text-dark font-medium mb-2">Número Interior (opcional)</label>
                    <input type="text" id="internal_number" name="internal_number" value="{{ old('internal_number') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div>
                    <label for="district" class="block text-oaxaca-text-dark font-medium mb-2">Colonia / Barrio <span class="text-red-500">*</span></label>
                    <input type="text" id="district" name="district" value="{{ old('district') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div>
                    <label for="city" class="block text-oaxaca-text-dark font-medium mb-2">Ciudad <span class="text-red-500">*</span></label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>

                <div>
                    <label for="state" class="block text-oaxaca-text-dark font-medium mb-2">Estado <span class="text-red-500">*</span></label>
                    <select id="state" name="state"
                            class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                        <option value="">-- Selecciona --</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->abbreviation }}" {{ old('state') == $state->abbreviation ? 'selected' : '' }}>
                                {{ $state->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="postal_code" class="block text-oaxaca-text-dark font-medium mb-2">Código Postal <span class="text-red-500">*</span></label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div>
                    <label for="country" class="block text-oaxaca-text-dark font-medium mb-2">País <span class="text-red-500">*</span></label>
                    <input type="text" id="country" name="country" value="{{ old('country', 'MX') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
                <div class="sm:col-span-2">
                    <label for="reference" class="block text-oaxaca-text-dark font-medium mb-2">Referencia (ej. entre calles, color de casa)</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference') }}"
                           class="w-full px-4 py-2 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-primary text-oaxaca-text-dark">
                </div>
            </fieldset>

            {{-- Botones de navegación --}}
            <div class="flex justify-between mt-8">
                <a href="{{ route('carrito.mostrar') }}" class="bg-gray-200 text-oaxaca-text-dark px-8 py-3 rounded-lg hover:bg-gray-300 transition-colors font-medium shadow-sm">
                    &larr; Volver al Carrito
                </a>
                <button type="submit" class="bg-oaxaca-primary text-white px-8 py-3 rounded-lg hover:bg-oaxaca-secondary transition-colors font-semibold shadow-md">
                    Continuar al método de envío &rarr;
                </button>
            </div>
        </form>
    </div>
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
            // Opcional: hacer que los campos requeridos sean realmente requeridos solo cuando el formulario está visible
            formFields.querySelectorAll('input, select').forEach(field => {
                if (field.name !== 'internal_number' && field.name !== 'reference') { // Excluir opcionales
                    field.setAttribute('required', 'required');
                }
            });
        } else {
            formFields.classList.add('hidden');
            // Remover 'required' cuando el formulario está oculto para evitar validación
            formFields.querySelectorAll('input, select').forEach(field => {
                field.removeAttribute('required');
            });
        }
    }

    radios.forEach(r => r.addEventListener('change', toggleFields));

    // Asegura que la lógica se ejecute al cargar la página,
    // especialmente si hay un 'old' valor para 'new' o si no hay direcciones guardadas
    toggleFields();

    // Si no hay direcciones guardadas, selecciona automáticamente "Capturar nueva dirección"
    const userShippingAddressesExist = {{ $userShippingAddresses->isNotEmpty() ? 'true' : 'false' }};
    if (!userShippingAddressesExist) {
        document.querySelector('input[name="selected_address_id"][value="new"]').checked = true;
        toggleFields(); // Vuelve a llamar para asegurar que el formulario se muestre
    }
});
</script>
@endsection
