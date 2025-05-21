@extends('layouts.public')

@section('title', 'Catálogo de Artesanías')

@section('content')
    <h1 class="text-4xl font-extrabold mb-8 text-center text-gray-900">Nuestras Artesanías de Oaxaca</h1>

    @if ($artesanias->isEmpty())
        <p class="text-center text-gray-600 text-lg">Parece que aún no hay artesanías en el catálogo. ¡Pronto tendremos más!</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($artesanias as $artesania)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <a href="{{ route('artesanias.show', $artesania->id) }}">
                        @if ($artesania->imagen_principal)
                            {{-- Asegúrate de que la ruta de almacenamiento esté configurada. `php artisan storage:link` --}}
                            <img src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}" class="w-full h-56 object-cover object-center">
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}" alt="Imagen no disponible" class="w-full h-56 object-cover object-center bg-gray-200">
                        @endif
                    </a>
                    <div class="p-5">
                        <h2 class="text-2xl font-bold mb-2 text-gray-900 leading-tight">
                            <a href="{{ route('artesanias.show', $artesania->id) }}" class="hover:text-blue-700 transition-colors">{{ $artesania->nombre }}</a>
                        </h2>
                        <p class="text-xl text-gray-700 font-semibold mb-3">${{ number_format($artesania->precio, 2) }} MXN</p>

                        <div class="text-sm text-gray-600 space-y-1">
                            @if ($artesania->artesano)
                                <p>Por: <a href="{{ route('artesanos.show', $artesania->artesano->id) }}" class="hover:underline text-blue-600">{{ $artesania->artesano->nombre }}</a></p>
                            @endif
                            @if ($artesania->categoria)
                                <p>Categoría: <a href="{{ route('categorias.show', $artesania->categoria->id) }}" class="hover:underline text-blue-600">{{ $artesania->categoria->nombre }}</a></p>
                            @endif
                            @if ($artesania->ubicacion)
                                <p>Origen: <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}" class="hover:underline text-blue-600">{{ $artesania->ubicacion->nombre }}</a></p>
                            @endif
                        </div>

                        <a href="{{ route('artesanias.show', $artesania->id) }}" class="mt-5 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center text-base font-medium">Ver Detalles</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection