@extends('layouts.public')

@section('title', 'Categorías de Artesanías - Raíces Artesanales MX')

@section('content')
    <div class="container mx-auto px-4 py-8"> {{-- Contenedor principal con padding --}}
        <section class="py-12 px-4 bg-oaxaca-bg-light rounded-xl mx-auto max-w-7xl shadow-lg mt-8 border border-oaxaca-primary border-opacity-10"> {{-- Fondo claro, sombra y borde --}}
            <h1 class="text-5xl md:text-6xl font-display font-bold text-center text-oaxaca-primary mb-10 animate-fade-in">Explora por Categoría</h1> {{-- Título principal con font-display y color primario --}}

            @if ($categorias->isEmpty())
                <p class="text-center text-oaxaca-text-dark text-xl py-8">No hay categorías disponibles en este momento. ¡Pronto tendremos más!</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($categorias as $categoria)
                        <div class="bg-oaxaca-card-bg rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 group border border-oaxaca-primary border-opacity-10"> {{-- Fondo de tarjeta, sombra y borde sutil --}}
                            <a href="{{ route('categorias.show', $categoria) }}">
                                {{-- Muestra la imagen de la categoría si existe, o el placeholder --}}
                                @if ($categoria->imagen)
                                    <img src="{{ asset('storage/' . $categoria->imagen) }}"
                                         alt="{{ $categoria->nombre }}"
                                         class="w-full h-56 object-cover object-center group-hover:opacity-90 transition-opacity duration-300"> {{-- Altura ajustada y efecto hover --}}
                                @else
                                    {{-- Usamos un placeholder genérico si la categoría no tiene imagen --}}
                                    <img src="{{ asset('storage/images/categorias/placeholder.jpg') }}" {{-- Asegúrate de tener un placeholder.jpg --}}
                                         alt="{{ $categoria->nombre }} (Sin imagen)"
                                         class="w-full h-56 object-cover object-center bg-gray-200 group-hover:opacity-90 transition-opacity duration-300">
                                @endif
                            </a>

                            <div class="p-6">
                                <h2 class="text-2xl font-display font-semibold text-oaxaca-primary mb-2"> {{-- Título de tarjeta con font-display y color primario --}}
                                    <a href="{{ route('categorias.show', $categoria) }}" class="hover:text-oaxaca-secondary transition-colors">{{ $categoria->nombre }}</a>
                                </h2>
                                <p class="text-oaxaca-text-dark text-base leading-relaxed mb-4">{{ $categoria->descripcion }}</p> {{-- Color de texto oscuro --}}
                                <a href="{{ route('categorias.show', $categoria) }}" class="mt-auto inline-block w-full bg-oaxaca-tertiary text-oaxaca-primary px-6 py-3 rounded-lg hover:bg-oaxaca-secondary hover:text-white transition-colors text-center text-lg font-semibold shadow-md transform hover:scale-105"> {{-- Botón con colores de marca --}}
                                    Ver Artesanías
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection