@extends('layouts.public')

@section('title', 'Ubicaciones de Artesanías - Raíces Artesanales MX')

@section('content')
    <section class="py-12 px-4 bg-oaxaca-bg-cream rounded-xl mx-auto max-w-7xl shadow-md mt-8">
        <h1 class="text-4xl md:text-5xl font-bold text-center text-oaxaca-title-pink mb-10">Descubre por Ubicación</h1>

        @if ($ubicaciones->isEmpty())
            <p class="text-center text-oaxaca-text-dark-gray text-lg">No hay ubicaciones disponibles en este momento.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($ubicaciones as $ubicacion)
                    <div class="bg-oaxaca-product-turquoise-light rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border-2 border-oaxaca-detail-emerald">
                        {{-- Podrías añadir una imagen de mapa o un icono representativo --}}
                        <div class="p-6">
                            <h2 class="text-2xl font-semibold text-oaxaca-title-pink mb-2">{{ $ubicacion->nombre }}</h2>
                            <p class="text-oaxaca-text-dark-gray text-md leading-relaxed mb-4">Tipo: <span class="font-semibold">{{ $ubicacion->tipo }}</span></p>
                            <p class="text-oaxaca-text-dark-gray text-md leading-relaxed mb-4">{{ Str::limit($ubicacion->descripcion, 100) }}</p>
                            <a href="{{ route('ubicaciones.show', $ubicacion) }}" class="inline-block w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-lg font-semibold shadow-md">
                                Ver Detalle
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection