@extends('layouts.public')

@section('title', $ubicacion->nombre . ' - Raíces Artesanales MX')

@section('content')
    <section class="py-12 px-4 bg-oaxaca-bg-cream rounded-xl mx-auto max-w-7xl shadow-md mt-8">
        <h1 class="text-4xl md:text-5xl font-bold text-center text-oaxaca-title-pink mb-6">{{ $ubicacion->nombre }}</h1>
        <p class="text-lg text-oaxaca-text-dark-gray text-center max-w-3xl mx-auto mb-4">Tipo: <span class="font-semibold">{{ $ubicacion->tipo }}</span></p>
        <p class="text-lg text-oaxaca-text-dark-gray text-center max-w-3xl mx-auto mb-10">{{ $ubicacion->descripcion }}</p>

        <h2 class="text-3xl md:text-4xl font-bold text-center text-oaxaca-navbar-blue mb-8">Artesanos de esta ubicación</h2>
        @if ($ubicacion->artesanos->isEmpty())
            <p class="text-center text-oaxaca-text-dark-gray text-lg mb-8">No hay artesanos registrados en esta ubicación por el momento.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach ($ubicacion->artesanos as $artesano)
                    <div class="bg-oaxaca-product-turquoise-light rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border-2 border-oaxaca-detail-emerald">
                        <div class="p-6">
                            <h3 class="text-2xl font-semibold text-oaxaca-title-pink mb-2">{{ $artesano->nombre }}</h3>
                            <p class="text-oaxaca-text-dark-gray text-md leading-relaxed mb-4">{{ Str::limit($artesano->biografia, 80) }}</p>
                            <a href="{{ route('artesanos.show', $artesano->id) }}" class="inline-block w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-lg font-semibold shadow-md">
                                Ver Artesano
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <h2 class="text-3xl md:text-4xl font-bold text-center text-oaxaca-navbar-blue mb-8">Artesanías de esta ubicación</h2>
        @if ($ubicacion->artesanias->isEmpty())
            <p class="text-center text-oaxaca-text-dark-gray text-lg">No hay artesanías directamente asociadas a esta ubicación por el momento.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($ubicacion->artesanias as $artesania)
                    <div class="bg-oaxaca-product-turquoise-light rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border-2 border-oaxaca-detail-emerald">
                        <img src="{{ asset($artesania->imagen_url ?? 'images/placeholder-artesania.jpg') }}" alt="{{ $artesania->nombre }}" class="gallery-img">
                        <div class="p-6">
                            <h3 class="text-2xl font-semibold text-oaxaca-navbar-blue mb-2">{{ $artesania->nombre }}</h3>
                            <p class="text-oaxaca-text-dark-gray text-md leading-relaxed">{{ Str::limit($artesania->descripcion, 100) }}</p>
                            @if ($artesania->artesano)
                                <p class="text-oaxaca-text-dark-gray text-sm mt-2">Por: <span class="font-semibold">{{ $artesania->artesano->nombre }}</span></p>
                            @endif
                            @if ($artesania->categoria)
                                <p class="text-oaxaca-text-dark-gray text-sm mt-1">Categoría: <span class="font-semibold">{{ $artesania->categoria->nombre }}</span></p>
                            @endif
                            <p class="text-oaxaca-navbar-orange font-bold text-xl mt-3">${{ number_format($artesania->precio, 2) }} MXN</p>
                            <a href="{{ route('artesanias.show', $artesania->id) }}" class="mt-5 inline-block w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-lg font-semibold shadow-md">
                                Ver Detalle
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection