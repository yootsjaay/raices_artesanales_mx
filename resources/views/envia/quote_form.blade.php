@extends('layouts.public')
@section('content')
<body>
    <h1>Cotizar Envío</h1>

    

    @if(isset($cotizacion))
        <h2>Respuesta:</h2>
        <pre>{{ print_r($cotizacion, true) }}</pre>
    @endif
</body>
@endsection