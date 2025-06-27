<h1>¡Pedido Confirmado!</h1>

@if (session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

<p>Gracias por tu compra. Tu pedido #{{ $order->id }} ha sido recibido.</p>
<p>El estado actual de tu pedido es: <strong>{{ ucfirst($order->status) }}</strong></p>
<p>Recibirás un correo electrónico de confirmación con los detalles de tu pedido.</p>

<h2>Detalles del Pedido</h2>
<p>Total Pagado: ${{ number_format($order->total_amount, 2) }}</p>
<h3>Productos:</h3>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product_name }} (x{{ $item->quantity }}) - ${{ number_format($item->price, 2) }}</li>
    @endforeach
</ul>

<h3>Dirección de Envío:</h3>
<p>{{ $order->shippingAddress->name }}</p>
<p>{{ $order->shippingAddress->address1 }} {{ $order->shippingAddress->address2 }}</p>
<p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>

<a href="{{ route('home') }}">Volver a la tienda</a>
<a href="{{ route('orders.show', $order) }}">Ver Detalles de mi Pedido</a> ```