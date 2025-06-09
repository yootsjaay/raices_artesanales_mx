@extends('layouts.public')

@section('title', 'Ubicaciones de Artesanías - Raíces Artesanales MX')

@section('content')
    <div class="container mx-auto px-4 py-8"> {{-- Contenedor principal con padding --}}
        <section class="py-12 px-4 bg-oaxaca-bg-light rounded-xl mx-auto max-w-7xl shadow-lg mt-8 border border-oaxaca-primary border-opacity-10"> {{-- Fondo claro, sombra y borde sutil --}}
            <h1 class="text-5xl md:text-6xl font-display font-bold text-center text-oaxaca-primary mb-10 animate-fade-in">Descubre por Ubicación</h1> {{-- Título principal con font-display y color primario --}}

            @if ($ubicaciones->isEmpty())
                <p class="text-center text-oaxaca-text-dark text-xl py-8">No hay ubicaciones disponibles en este momento. ¡Pronto tendremos más lugares mágicos para explorar!</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"> {{-- Grid ajustado para más columnas en pantallas grandes --}}
                    @foreach ($ubicaciones as $ubicacion)
                        <div class="bg-oaxaca-card-bg rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 group border border-oaxaca-primary border-opacity-10"> {{-- Fondo de tarjeta, sombra y borde sutil --}}
                            <a href="{{ route('ubicaciones.show', $ubicacion) }}">
                                {{-- Placeholder para la imagen de ubicación. Puedes usar un icono o una imagen genérica. --}}
                                {{-- Si tienes un campo 'imagen' en tu modelo Ubicacion, úsalo aquí. --}}
                                @if ($ubicacion->imagen) {{-- Asumiendo que hay un campo 'imagen' --}}
                                    <img src="{{ asset('storage/' . $ubicacion->imagen) }}"
                                         alt="Ubicación de {{ $ubicacion->nombre }}"
                                         class="w-full h-48 object-cover object-center group-hover:opacity-90 transition-opacity duration-300">
                                @else
                                    <img src="{{ asset('storage/images/ubicaciones/placeholder-oaxaca.jpg') }}" {{-- Asegúrate de tener esta imagen --}}
                                         alt="Mapa de {{ $ubicacion->nombre }}"
                                         class="w-full h-48 object-cover object-center bg-gray-200 group-hover:opacity-90 transition-opacity duration-300">
                                @endif
                            </a>
                            <div class="p-6">
                                <h2 class="text-2xl font-display font-semibold text-oaxaca-primary mb-2"> {{-- Título de ubicación con font-display y color primario --}}
                                    <a href="{{ route('ubicaciones.show', $ubicacion) }}" class="hover:text-oaxaca-secondary transition-colors">{{ $ubicacion->nombre }}</a>
                                </h2>
                                <p class="text-oaxaca-text-dark text-base leading-relaxed mb-4">Tipo: <span class="font-semibold">{{ $ubicacion->tipo }}</span></p> {{-- Color de texto oscuro --}}
                                <p class="text-oaxaca-text-dark text-base leading-relaxed mb-4">{{ Str::limit($ubicacion->descripcion, 90) }}</p> {{-- Descripción con color de texto oscuro y límite --}}
                                <a href="{{ route('ubicaciones.show', $ubicacion) }}" class="mt-auto inline-block w-full bg-oaxaca-tertiary text-oaxaca-primary px-6 py-3 rounded-lg hover:bg-oaxaca-secondary hover:text-white transition-colors text-center text-lg font-semibold shadow-md transform hover:scale-105"> {{-- Botón con colores de marca --}}
                                    Ver Detalle
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection