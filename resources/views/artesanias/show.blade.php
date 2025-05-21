@extends('layouts.public')

@section('title', $artesania->nombre)

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <a href="{{ route('artesanias.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Catálogo
        </a>

        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $artesania->nombre }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                @if ($artesania->imagen_principal)
                    <img src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md">
                @else
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Imagen no disponible" class="w-full h-96 object-cover rounded-lg shadow-md bg-gray-200">
                @endif

                @if ($artesania->imagenes_adicionales && count(json_decode($artesania->imagenes_adicionales)) > 0)
                    <div class="mt-4 grid grid-cols-3 gap-2">
                        @foreach (json_decode($artesania->imagenes_adicionales) as $extraImage)
                            <img src="{{ asset('storage/' . $extraImage) }}" alt="Imagen adicional" class="w-full h-24 object-cover rounded-md shadow-sm cursor-pointer hover:opacity-75 transition-opacity">
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="text-3xl font-bold text-blue-700 mb-4">${{ number_format($artesania->precio, 2) }} MXN</p>
                <p class="text-gray-700 text-lg mb-6 leading-relaxed">{{ $artesania->descripcion }}</p>

                <div class="text-base text-gray-800 space-y-2 mb-6">
                    @if ($artesania->artesano)
                        <p><strong>Artesano:</strong> <a href="{{ route('artesanos.show', $artesania->artesano->id) }}" class="text-blue-600 hover:underline">{{ $artesania->artesano->nombre }}</a></p>
                    @endif
                    @if ($artesania->categoria)
                        <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->id) }}" class="text-blue-600 hover:underline">{{ $artesania->categoria->nombre }}</a></p>
                    @endif
                    @if ($artesania->ubicacion)
                        <p><strong>Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}" class="text-blue-600 hover:underline">{{ $artesania->ubicacion->nombre }}</a></p>
                    @endif
                    <p><strong>Stock:</strong> {{ $artesania->stock > 0 ? $artesania->stock . ' disponibles' : 'Agotado' }}</p>
                    <p><strong>Técnica:</strong> {{ $artesania->tecnica_empleada ?? 'N/A' }}</p>
                    <p><strong>Materiales:</strong> {{ $artesania->materiales ?? 'N/A' }}</p>
                    <p><strong>Dimensiones:</strong> {{ $artesania->dimensiones ?? 'N/A' }}</p>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-3">La Historia Detrás de la Pieza</h3>
                <p class="text-gray-700 leading-relaxed">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                {{-- Aquí iría un botón "Agregar al Carrito" o "Contactar Artesano" --}}
            </div>
        </div>
    </div>
@endsection