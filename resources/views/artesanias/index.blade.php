@extends('comprador.layouts.public')

@section('title', 'Catálogo de Artesanías')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-5xl font-display font-bold mb-10 text-center text-oaxaca-primary animate-fade-in">Descubre Nuestros Tesoros Artesanales</h1>

        @if ($artesanias->isEmpty())
            <p class="text-center text-oaxaca-text-dark text-xl py-12">
                Parece que aún no hay artesanías en el catálogo. ¡Pronto tendremos más piezas únicas para ti!
            </p>
            <div class="text-center mt-8">
                <a href="{{ url('/') }}" class="inline-block bg-oaxaca-tertiary text-white px-8 py-3 rounded-full hover:bg-oaxaca-primary transition-colors text-lg font-medium shadow-md">
                    Volver al Inicio
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($artesanias as $artesania)
                    <div class="bg-oaxaca-card-bg rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-xl group border border-oaxaca-primary border-opacity-10">
                        <a href="{{ route('artesanias.show', $artesania->id) }}">
                            @if ($artesania->imagen_principal)
                                <img src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}"
                                     class="w-full h-64 object-cover object-center group-hover:opacity-90 transition-opacity duration-300">
                            @else
                                <img src="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" alt="Imagen no disponible"
                                     class="w-full h-64 object-cover object-center bg-gray-200 group-hover:opacity-90 transition-opacity duration-300">
                            @endif
                        </a>
                        <div class="p-6">
                            <h2 class="text-2xl font-display font-semibold mb-2 text-oaxaca-primary leading-tight">
                                <a href="{{ route('artesanias.show', $artesania->id) }}" class="hover:text-oaxaca-secondary transition-colors">
                                    {{ $artesania->nombre }}
                                </a>
                            </h2>
                            <p class="text-2xl text-oaxaca-tertiary font-bold mb-4">${{ number_format($artesania->precio, 2) }} MXN</p>

                            <div class="text-base text-oaxaca-text-dark space-y-2">
                                @if ($artesania->categoria)
                                    <p>
                                        <span class="font-semibold">Categoría:</span>
                                        <a href="{{ route('categorias.show', $artesania->categoria->id) }}"
                                           class="text-oaxaca-accent hover:underline transition-colors">
                                            {{ $artesania->categoria->nombre }}
                                        </a>
                                    </p>
                                @endif
                                @if ($artesania->ubicacion)
                                    <p>
                                        <span class="font-semibold">Origen:</span>
                                        <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}"
                                           class="text-oaxaca-accent hover:underline transition-colors">
                                            {{ $artesania->ubicacion->nombre }}
                                        </a>
                                    </p>
                                @endif
                            </div>

                            <a href="{{ route('artesanias.show', $artesania->slug) }}"
                               class="mt-6 inline-block bg-oaxaca-primary text-white px-8 py-3 rounded-full hover:bg-oaxaca-secondary transition-colors text-center text-lg font-medium shadow-md">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection