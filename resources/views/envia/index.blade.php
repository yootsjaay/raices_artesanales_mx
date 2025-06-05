@extends('layouts.public')

@section('content')
<div class="container">
    <h1 class="mb-4">Cotizar Envío</h1>

    {{-- Mostrar errores de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Errores:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mostrar mensaje de error --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formulario de cotización --}}
    <form method="POST" action="{{ route('envia.quote') }}">
        @csrf

        <h5>Origen</h5>
        <div class="row mb-3">
            <div class="col">
                <label>Código Postal</label>
                <input type="text" name="origin_postal" class="form-control" value="{{ old('origin_postal', $defaultData['origin_postal']) }}">
            </div>
            <div class="col">
                <label>Ciudad</label>
                <input type="text" name="origin_city" class="form-control" value="{{ old('origin_city', $defaultData['origin_city']) }}">
            </div>
            <div class="col">
                <label>Estado</label>
                <input type="text" name="origin_state" class="form-control" value="{{ old('origin_state', $defaultData['origin_state']) }}">
            </div>
        </div>

        <h5>Destino</h5>
        <div class="row mb-3">
            <div class="col">
                <label>Código Postal</label>
                <input type="text" name="destination_postal" class="form-control" value="{{ old('destination_postal', $defaultData['destination_postal']) }}">
            </div>
            <div class="col">
                <label>Ciudad</label>
                <input type="text" name="destination_city" class="form-control" value="{{ old('destination_city', $defaultData['destination_city']) }}">
            </div>
            <div class="col">
                <label>Estado</label>
                <input type="text" name="destination_state" class="form-control" value="{{ old('destination_state', $defaultData['destination_state']) }}">
            </div>
        </div>

        <h5>Paquetes</h5>
        @foreach($defaultData['packages'] as $i => $package)
            <div class="row mb-3">
                <div class="col">
                    <label>Peso (kg)</label>
                    <input type="number" step="0.01" name="packages[{{ $i }}][weight]" class="form-control" value="{{ old("packages.$i.weight", $package['weight']) }}">
                </div>
                <div class="col">
                    <label>Alto (cm)</label>
                    <input type="number" step="0.1" name="packages[{{ $i }}][height]" class="form-control" value="{{ old("packages.$i.height", $package['height']) }}">
                </div>
                <div class="col">
                    <label>Ancho (cm)</label>
                    <input type="number" step="0.1" name="packages[{{ $i }}][width]" class="form-control" value="{{ old("packages.$i.width", $package['width']) }}">
                </div>
                <div class="col">
                    <label>Largo (cm)</label>
                    <input type="number" step="0.1" name="packages[{{ $i }}][length]" class="form-control" value="{{ old("packages.$i.length", $package['length']) }}">
                </div>
                <div class="col">
                    <label>Valor Declarado ($MXN)</label>
                    <input type="number" step="1" name="packages[{{ $i }}][declaredValue]" class="form-control" value="{{ old("packages.$i.declaredValue", $package['declaredValue']) }}">
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Cotizar</button>
    </form>

    {{-- Resultados de cotización --}}
    @if(!empty($quotes))
        <h3 class="mt-5">Resultados</h3>
        @foreach ($quotes as $carrier => $quote)
    <h5>{{ strtoupper($carrier) }}</h5>

    @if (empty($quote['data']))
        <p>No se encontraron cotizaciones disponibles para este carrier.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Precio</th>
                    <th>Tiempo estimado</th>
                    <th>Moneda</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quote['data'] as $option)
                    <tr>
                        <td>{{ data_get($option, 'service', 'N/D') }}</td>
                        <td>${{ number_format(data_get($option, 'price', 0), 2) }}</td>
                        <td>{{ data_get($option, 'estimatedTime', 'N/A') }}</td>
                        <td>{{ data_get($option, 'currency', 'MXN') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endforeach

    @endif
</div>
@endsection
