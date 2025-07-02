@extends('comprador.layouts.public')

@section('title', $categoria->nombre . ' - Raíces Artesanales MX')

@section('content')
    <div class="container mx-auto px-4 py-8"> {{-- Contenedor principal con padding --}}
        <section class="py-12 px-4 bg-oaxaca-bg-light rounded-xl mx-auto max-w-7xl shadow-lg mt-8 border border-oaxaca-primary border-opacity-10"> {{-- Fondo claro, sombra y borde --}}

            <a href="{{ route('categorias.index') }}" class="inline-flex items-center text-oaxaca-primary hover:text-oaxaca-secondary transition-colors mb-6 font-semibold"> {{-- Color del enlace "Volver" --}}
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Categorías
            </a>

            <h1 class="text-5xl md:text-6xl font-display font-bold text-center text-oaxaca-primary mb-6 leading-tight">{{ $categoria->nombre }}</h1> {{-- Título con font-display y color primario --}}
            <p class="text-xl text-oaxaca-text-dark text-center max-w-4xl mx-auto mb-10 leading-relaxed">{{ $categoria->descripcion }}</p> {{-- Color y tamaño del texto, más espaciado --}}

            <h2 class="text-4xl md:text-5xl font-display font-bold text-center text-oaxaca-secondary mb-10 mt-12">Artesanías en esta categoría</h2> {{-- Título secundario con font-display y color secundario --}}

            @if ($categoria->artesanias->isEmpty())
                <p class="text-center text-oaxaca-text-dark text-xl py-8">No hay artesanías disponibles en esta categoría por el momento. ¡Pronto tendremos más piezas únicas!</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"> {{-- Grid ajustado, ahora 4 columnas en lg --}}
                    @foreach ($categoria->artesanias as $artesania)
                        <div class="bg-oaxaca-card-bg rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 group border border-oaxaca-primary border-opacity-10"> {{-- Fondo de tarjeta, sombra y borde sutil --}}
                            <a href="{{ route('artesanias.show', $artesania->id) }}">
                                @if ($artesania->imagen_principal)
                                    <img src="{{ asset('storage/' . $artesania->imagen_principal) }}"
                                         alt="{{ $artesania->nombre }}"
                                         class="w-full h-56 object-cover object-center group-hover:opacity-90 transition-opacity duration-300"> {{-- Altura ajustada y efecto hover --}}
                                @else
                                    <img src="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}"
                                         alt="Imagen no disponible"
                                         class="w-full h-56 object-cover object-center bg-gray-200 group-hover:opacity-90 transition-opacity duration-300">
                                @endif
                            </a>
                            <div class="p-5"> {{-- Padding ajustado --}}
                                <h3 class="text-2xl font-display font-semibold mb-2 text-oaxaca-primary leading-tight"> {{-- Título de artesanía con font-display y color primario --}}
                                    <a href="{{ route('artesanias.show', $artesania->id) }}" class="hover:text-oaxaca-secondary transition-colors">{{ $artesania->nombre }}</a>
                                </h3>
                                <p class="text-base text-oaxaca-text-dark leading-relaxed mb-3">{{ Str::limit($artesania->descripcion, 90) }}</p> {{-- Descripción con color de texto oscuro --}}
                                @if ($artesania->artesano)
                                    <p class="text-oaxaca-text-dark text-sm mt-2">Por: <a href="{{ route('artesanos.show', $artesania->artesano->id) }}" class="font-semibold text-oaxaca-accent hover:underline">{{ $artesania->artesano->nombre }}</a></p> {{-- Enlace al artesano con color de acento --}}
                                @endif
                                <p class="text-2xl text-oaxaca-tertiary font-bold mt-3 mb-4">${{ number_format($artesania->precio, 2) }} MXN</p> {{-- Precio más grande y color terciario --}}

                                <a href="{{ route('artesanias.show', $artesania->id) }}" class="mt-auto inline-block w-full bg-oaxaca-primary text-white px-6 py-3 rounded-lg hover:bg-oaxaca-secondary transition-colors text-center text-lg font-medium shadow-md transform hover:scale-105"> {{-- Botón con colores primarios y hover --}}
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection