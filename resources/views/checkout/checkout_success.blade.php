<h2>¡Gracias por tu compra!</h2>

@if(session('shipment_id'))
    <p>Tu guía de envío fue generada con éxito.</p>
    <p>🔗 <a href="{{ \App\Models\Shipment::find(session('shipment_id'))->label_url }}" target="_blank">Descargar etiqueta PDF</a></p>
@else
    <p>No se pudo generar la guía. Intenta de nuevo.</p>
@endif

<a href="{{ route('home') }}">Volver al inicio</a>
