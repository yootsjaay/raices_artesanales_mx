<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Artesanías - Raíces Artesanales MX</title>
    </head>
<body>
    <h1>Todas las Artesanías</h1>

    @if ($artesanias->isEmpty())
        <p>No hay artesanías disponibles en este momento.</p>
    @else
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            @foreach ($artesanias as $artesania)
                <div style="border: 1px solid #ccc; padding: 15px; width: 300px;">
                    <h2><a href="{{ route('artesanias.show', $artesania->id) }}">{{ $artesania->nombre }}</a></h2>
                    <p>Precio: ${{ number_format($artesania->precio, 2) }}</p>
                    @if ($artesania->artesano)
                        <p>Artesano: <a href="{{ route('artesanos.show', $artesania->artesano->id) }}">{{ $artesania->artesano->nombre }}</a></p>
                    @endif
                    @if ($artesania->categoria)
                        <p>Categoría: <a href="{{ route('categorias.show', $artesania->categoria->id) }}">{{ $artesania->categoria->nombre }}</a></p>
                    @endif
                    @if ($artesania->ubicacion)
                        <p>Ubicación: <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}">{{ $artesania->ubicacion->nombre }}</a></p>
                    @endif
                    </div>
            @endforeach
        </div>
    @endif
</body>
</html>