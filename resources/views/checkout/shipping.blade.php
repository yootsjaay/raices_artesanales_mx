@extends(app.public)

@section('content')

<form action="{{ route('checkout.process') }}" method="POST">
    @csrf

    <h3>Datos de envío</h3>
    
    <input type="text" name="name" placeholder="Nombre completo" required>
    <input type="text" name="company" placeholder="Empresa (opcional)">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="text" name="phone" placeholder="Teléfono" required>
    <input type="text" name="street" placeholder="Calle" required>
    <input type="text" name="number" placeholder="Número" required>
    <input type="text" name="district" placeholder="Colonia / Distrito">
    <input type="text" name="city" placeholder="Ciudad" required>
    <input type="text" name="state" placeholder="Estado" required>
    <input type="text" name="postal_code" placeholder="Código postal" required>
    <input type="text" name="reference" placeholder="Referencia">

    <button type="submit">Finalizar compra y generar guía</button>
</form>

@endesection
@script(js)
