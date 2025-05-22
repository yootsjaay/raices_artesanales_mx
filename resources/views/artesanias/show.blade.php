@extends('layouts.public')

@section('title', $artesania->nombre . ' - Raíces Artesanales MX')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8">
        <a href="{{ route('artesanias.index') }}" class="inline-flex items-center text-oaxaca-navbar-blue hover:text-oaxaca-title-pink transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Catálogo
        </a>

        <h1 class="text-4xl font-extrabold text-oaxaca-title-pink mb-4">{{ $artesania->nombre }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            {{-- Columna de Imágenes --}}
            <div>
                {{-- Imagen Principal --}}
                @if ($artesania->imagen_principal)
                    {{-- Enlace para Lightbox de la imagen principal --}}
                    <a href="{{ asset('storage/' . $artesania->imagen_principal) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }}">
                        <img src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md cursor-zoom-in">
                    </a>
                @else
                    <a href="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" data-lightbox="artesania-gallery" data-title="Imagen no disponible">
                        <img src="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" alt="Imagen no disponible" class="w-full h-96 object-cover rounded-lg shadow-md bg-gray-200 cursor-zoom-in">
                    </a>
                @endif

                {{-- Galería de Imágenes Adicionales --}}
                @if ($artesania->imagen_adicionales)
                    @php
                        $imagenesAdicionales = json_decode($artesania->imagen_adicionales, true);
                    @endphp
                    @if (is_array($imagenesAdicionales) && count($imagenesAdicionales) > 0)
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            @foreach ($imagenesAdicionales as $extraImage)
                                {{-- Enlace para Lightbox de cada imagen adicional --}}
                                <a href="{{ asset('storage/' . $extraImage) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }} - Vista adicional">
                                    <img src="{{ asset('storage/' . $extraImage) }}" alt="Imagen adicional de {{ $artesania->nombre }}" class="w-full h-24 object-cover rounded-md shadow-sm cursor-zoom-in hover:opacity-75 transition-opacity">
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            {{-- Columna de Detalles de la Artesanía --}}
            <div>
                <p class="text-3xl font-bold text-oaxaca-navbar-orange mb-4">${{ number_format($artesania->precio, 2) }} MXN</p>
                <p class="text-oaxaca-text-dark-gray text-lg mb-6 leading-relaxed">{{ $artesania->descripcion }}</p>

                <div class="text-base text-oaxaca-text-dark-gray space-y-2 mb-6">
                    @if ($artesania->artesano)
                        <p><strong>Artesano:</strong> <a href="{{ route('artesanos.show', $artesania->artesano->id) }}" class="text-oaxaca-navbar-blue hover:underline">{{ $artesania->artesano->nombre }}</a></p>
                    @endif
                    @if ($artesania->categoria)
                        <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->id) }}" class="text-oaxaca-navbar-blue hover:underline">{{ $artesania->categoria->nombre }}</a></p>
                    @endif
                    @if ($artesania->ubicacion)
                        <p><strong>Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}" class="text-oaxaca-navbar-blue hover:underline">{{ $artesania->ubicacion->nombre }}</a></p>
                    @endif
                    <p><strong>Stock:</strong> {{ $artesania->stock > 0 ? $artesania->stock . ' disponibles' : 'Agotado' }}</p>
                    <p><strong>Técnica:</strong> {{ $artesania->tecnica_empleada ?? 'N/A' }}</p>
                    <p><strong>Materiales:</strong> {{ $artesania->materiales ?? 'N/A' }}</p>
                    <p><strong>Dimensiones:</strong> {{ $artesania->dimensiones ?? 'N/A' }}</p>
                </div>

                <h3 class="text-2xl font-bold text-oaxaca-navbar-blue mb-3">La Historia Detrás de la Pieza</h3>
                <p class="text-oaxaca-text-dark-gray leading-relaxed">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                <div class="mt-8">
                    <button class="w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-xl font-semibold shadow-md">
                        Añadir al Carrito
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection