{{-- resources/views/envia/index.blade.php --}}

@extends('layouts.public')

@section('content')
<div class="container">
    <h1>Cotización Envia</h1>

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('envia.quote') }}">
        @csrf

        <h3>Datos Origen</h3>
        <input type="text" name="origin_postal" placeholder="Código Postal Origen" value="{{ old('origin_postal', $defaultData['origin_postal'] ?? '') }}" required>
        <input type="text" name="origin_state" placeholder="Estado Origen" value="{{ old('origin_state', $defaultData['origin_state'] ?? '') }}">
        <input type="text" name="origin_city" placeholder="Ciudad Origen" value="{{ old('origin_city', $defaultData['origin_city'] ?? '') }}">
        <input type="text" name="origin_street" placeholder="Calle Origen" value="{{ old('origin_street', $defaultData['origin_street'] ?? '') }}">
        <input type="text" name="origin_number" placeholder="Número Origen" value="{{ old('origin_number', $defaultData['origin_number'] ?? '') }}">

        <h3>Datos Destino</h3>
        <input type="text" name="destination_postal" placeholder="Código Postal Destino" value="{{ old('destination_postal', $defaultData['destination_postal'] ?? '') }}" required>
        <input type="text" name="destination_state" placeholder="Estado Destino" value="{{ old('destination_state', $defaultData['destination_state'] ?? '') }}">
        <input type="text" name="destination_city" placeholder="Ciudad Destino" value="{{ old('destination_city', $defaultData['destination_city'] ?? '') }}">
        <input type="text" name="destination_street" placeholder="Calle Destino" value="{{ old('destination_street', $defaultData['destination_street'] ?? '') }}">
        <input type="text" name="destination_number" placeholder="Número Destino" value="{{ old('destination_number', $defaultData['destination_number'] ?? '') }}">

        <h3>Paquete 1</h3>
        <input type="number" step="0.01" name="packages[0][weight]" placeholder="Peso (kg)" value="{{ old('packages.0.weight', $defaultData['weight'] ?? '') }}" required>
        <input type="number" step="0.01" name="packages[0][height]" placeholder="Alto (cm)" value="{{ old('packages.0.height', $defaultData['height'] ?? '') }}" required>
        <input type="number" step="0.01" name="packages[0][width]" placeholder="Ancho (cm)" value="{{ old('packages.0.width', $defaultData['width'] ?? '') }}" required>
        <input type="number" step="0.01" name="packages[0][length]" placeholder="Largo (cm)" value="{{ old('packages.0.length', $defaultData['length'] ?? '') }}" required>
        <input type="number" step="0.01" name="packages[0][declaredValue]" placeholder="Valor declarado (MXN)" value="{{ old('packages.0.declaredValue', $defaultData['declaredValue'] ?? '') }}">

        <br><br>
        <button type="submit">Obtener Cotización</button>
    </form>

    @if($quotes)
        <h2>Resultados de la cotización:</h2>
        <pre>{{ print_r($quotes, true) }}</pre>
    @endif
</div>
@endsection
