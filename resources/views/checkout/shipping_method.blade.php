<h1>Selecciona un Método de Envío</h1>

@if (session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif

@if (empty($shippingOptions))
    <p>No se encontraron opciones de envío para tu dirección. Por favor, verifica tu dirección de envío o inténtalo de nuevo más tarde.</p>
    <a href="{{ route('checkout.shipping') }}">Volver a Dirección de Envío</a>
@else
    <form action="{{ route('checkout.process_shipping_method') }}" method="POST">
        @csrf
        @foreach ($shippingOptions as $option)
            <div>
                <input type="radio"
                       name="shipping_option"
                       value="{{ json_encode($option) }}" {{ $loop->first ? 'checked' : '' }}
                       id="shipping-option-{{ $option['carrier'] }}-{{ $option['service'] }}">
                <label for="shipping-option-{{ $option['carrier'] }}-{{ $option['service'] }}">
                    <strong>{{ $option['carrier'] ?? 'Transportista Desconocido' }} - {{ $option['serviceDescription'] ?? 'Servicio Desconocido' }}</strong><br>
                    Costo: ${{ number_format($option['totalPrice'] ?? 0, 2) }} {{ $option['currency'] ?? 'MXN' }}<br>
                    Tiempo estimado: {{ $option['deliveryEstimate'] ?? 'N/A' }}
                </label>
            </div>
            <hr>
        @endforeach
        <button type="submit">Continuar al Pago</button>
    </form>
@endif