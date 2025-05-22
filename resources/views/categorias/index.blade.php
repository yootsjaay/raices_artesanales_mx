@extends('layouts.public')

@section('title', 'Categorías de Artesanías - Raíces Artesanales MX')

@section('content')
    <section class="py-12 px-4 bg-oaxaca-bg-cream rounded-xl mx-auto max-w-7xl shadow-md mt-8">
        <h1 class="text-4xl md:text-5xl font-bold text-center text-oaxaca-title-pink mb-10">Explora por Categoría</h1>

        @if ($categorias->isEmpty())
            <p class="text-center text-oaxaca-text-dark-gray text-lg">No hay categorías disponibles en este momento.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($categorias as $categoria)
                    <div class="bg-oaxaca-product-turquoise-light rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border-2 border-oaxaca-detail-emerald">
                        {{-- Muestra la imagen de la categoría si existe, o el placeholder --}}
                        @if ($categoria->imagen)
                            {{-- Ruta corregida: $categoria->imagen ya contiene 'images/categorias/nombre.jpg' --}}
                            <img src="{{ asset('storage/' . $categoria->imagen) }}" 
                                 alt="{{ $categoria->nombre }}" 
                                 class="w-full h-48 object-cover object-center">
                        @else
                            {{-- Usamos un placeholder genérico si la categoría no tiene imagen --}}
                            <img src="{{ asset('storage/images/categorias/alebrijes.jpg') }}" 
                                 alt="{{ $categoria->nombre }} (Sin imagen)" 
                                 class="w-full h-48 object-cover object-center bg-gray-200">
                        @endif

                        <div class="p-6">
                            <h2 class="text-2xl font-semibold text-oaxaca-title-pink mb-2">{{ $categoria->nombre }}</h2>
                            <p class="text-oaxaca-text-dark-gray text-md leading-relaxed mb-4">{{ $categoria->descripcion }}</p>
                            <a href="{{ route('categorias.show', $categoria) }}" class="inline-block w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-lg font-semibold shadow-md">
                                Ver Artesanías
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection