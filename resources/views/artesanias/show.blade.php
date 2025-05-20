<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $artesania->nombre }} - Raíces Artesanales MX</title>
</head>
<body>
    <a href="{{ route('artesanias.index') }}">Volver al catálogo</a>
    <h1>{{ $artesania->nombre }}</h1>
    <p><strong>Descripción:</strong> {{ $artesania->descripcion }}</p>
    <p><strong>Precio:</strong> ${{ number_format($artesania->precio, 2) }}</p>
    <p><strong>Stock:</strong> {{ $artesania->stock }}</p>

    @if ($artesania->artesano)
        <p><strong>Artesano:</strong> <a href="{{ route('artesanos.show', $artesania->artesano->id) }}">{{ $artesania->artesano->nombre }}</a></p>
    @endif
    @if ($artesania->categoria)
        <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->id) }}">{{ $artesania->categoria->nombre }}</a></p>
    @endif
    @if ($artesania->ubicacion)
        <p><strong>Ubicación de Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}">{{ $artesania->ubicacion->nombre }}</a></p>
    @endif

    <p><strong>Técnica Empleada:</strong> {{ $artesania->tecnica_empleada }}</p>
    <p><strong>Materiales:</strong> {{ $artesania->materiales }}</p>
    <p><strong>Dimensiones:</strong> {{ $artesania->dimensiones }}</p>
    <p><strong>Historia de la pieza:</strong> {{ $artesania->historia_pieza }}</p>

    @if ($artesania->imagen_principal)
        <img src="/storage/{{ $artesania->imagen_principal }}" alt="Imagen principal de {{ $artesania->nombre }}" style="max-width: 400px;">
    @endif
    </body>
</html>