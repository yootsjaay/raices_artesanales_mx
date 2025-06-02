@extends('layouts.public')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white shadow p-6 rounded">
    <h2 class="text-xl font-bold mb-4">Cotizar Envío</h2>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('envia.quote.post') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>Nombre</label>
                <input type="text" name="destination[name]" class="form-input w-full" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="destination[email]" class="form-input w-full" required>
            </div>
            <div>
                <label>Teléfono</label>
                <input type="text" name="destination[phone]" class="form-input w-full" required>
            </div>
            <div>
                <label>Calle</label>
                <input type="text" name="destination[street]" class="form-input w-full" required>
            </div>
            <div>
                <label>Número</label>
                <input type="text" name="destination[number]" class="form-input w-full" required>
            </div>
            <div>
                <label>Colonia</label>
                <input type="text" name="destination[district]" class="form-input w-full" required>
            </div>
            <div>
                <label>Código Postal</label>
                <input type="text" name="destination[zip]" class="form-input w-full" required>
            </div>
            <div>
                <label>Ciudad</label>
                <input type="text" name="destination[city]" class="form-input w-full" required>
            </div>
            <div>
                <label>Estado</label>
                <input type="text" name="destination[state]" class="form-input w-full" required>
            </div>
        </div>

        <button type="submit" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Obtener Cotizaciones
        </button>
    </form>
</div>
@endsection
