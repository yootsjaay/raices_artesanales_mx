@extends('layouts.public')

@section('title', 'Raíces Artesanales MX - Colores de Oaxaca')

@section('content')
    <header class="bg-oaxaca-hero-gradient from-gradient-start-pink to-gradient-end-turquoise text-oaxaca-text-white text-center py-16 md:py-24 px-4 shadow-lg"> {{-- ¡Degradado de rosa mexicano a turquesa! Texto blanco --}}
        <h1 class="text-5xl md:text-6xl font-bold mb-4 drop-shadow-md">Raíces Artesanales MX</h1>
        <p class="text-xl md:text-2xl mb-6 leading-relaxed">Artesanías únicas: Alebrijes, Barro Policromado, Textiles y más de Oaxaca</p>
        <a href="#productos" class="bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-8 py-4 rounded-full font-semibold text-lg hover:bg-oaxaca-button-mustard-hover transition-colors shadow-lg"> {{-- Botón amarillo mostaza, texto gris --}}
            Explora Nuestra Colección
        </a>
    </header>

    <section id="productos" class="py-12 px-4 bg-oaxaca-bg-cream rounded-xl mx-auto max-w-7xl shadow-md"> {{-- Fondo principal crema cálido para la sección --}}
        <h2 class="text-4xl md:text-5xl font-bold text-center text-oaxaca-title-pink mb-10">Nuestras Artesanías</h2> {{-- Título en rosa mexicano --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-oaxaca-product-turquoise-light rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border-2 border-oaxaca-detail-emerald"> {{-- Fondo turquesa suave, borde esmeralda --}}
                <img src="{{ asset('images/alebrije_placeholder.jpg') }}" alt="Alebrije" class="gallery-img">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-oaxaca-title-pink mb-2">Alebrijes</h3> {{-- Título de tarjeta en rosa mexicano --}}
                    <p class="text-oaxaca-text-dark-gray text-md leading-relaxed">Figuras fantásticas talladas en madera de copal y pintadas con colores vibrantes, inspiradas en la tradición oaxaqueña.</p>
                    <p class="text-oaxaca-navbar-orange font-bold text-xl mt-3">$500 - $5,000 MXN</p> {{-- Precios en naranja quemado --}}
                    <a href="{{ route('categorias.show', 1) }}" class="mt-5 inline-block w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-lg font-semibold shadow-md">
                        Ver Más
                    </a>
                </div>
            </div>

            <div class="bg-oaxaca-product-turquoise-light rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border-2 border-oaxaca-detail-emerald">
                <img src="{{ asset('images/barro_placeholder.jpg') }}" alt="Barro Rojo" class="gallery-img">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-oaxaca-title-pink mb-2">Barro Rojo</h3>
                    <p class="text-oaxaca-text-dark-gray text-md leading-relaxed">Piezas de cerámica pintadas a mano con colores vivos, reflejo de la rica herencia cultural mexicana.</p>
                    <p class="text-oaxaca-navbar-orange font-bold text-xl mt-3">$200 - $2,000 MXN</p>
                    <a href="{{ route('categorias.show', 2) }}" class="mt-5 inline-block w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-lg font-semibold shadow-md">
                        Ver Más
                    </a>
                </div>
            </div>

            <div class="bg-oaxaca-product-turquoise-light rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border-2 border-oaxaca-detail-emerald">
                <img src="{{ asset('images/textil_placeholder.jpg') }}" alt="Ropa Tradicional" class="gallery-img">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-oaxaca-title-pink mb-2">Ropa Tradicional</h3>
                    <p class="text-oaxaca-text-dark-gray text-md leading-relaxed">Huipiles y rebozos bordados a mano, llenos de color y tradición, perfectos para cualquier ocasión.</p>
                    <p class="text-oaxaca-navbar-orange font-bold text-xl mt-3">$800 - $3,000 MXN</p>
                    <a href="{{ route('categorias.show', 3) }}" class="mt-5 inline-block w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-lg font-semibold shadow-md">
                        Ver Más
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-oaxaca-bg-cream py-12 px-4 rounded-xl shadow-md mx-auto max-w-7xl mt-8 border border-oaxaca-detail-emerald"> {{-- Fondo crema, borde esmeralda --}}
        <h2 class="text-4xl md:text-5xl font-bold text-center text-oaxaca-title-pink mb-8">Sobre Nosotros</h2>
        <p class="text-lg text-oaxaca-text-dark-gray max-w-3xl mx-auto text-center leading-relaxed">
            En **Raíces Artesanales MX**, celebramos la riqueza cultural de México a través de nuestras artesanías. Cada pieza es hecha a mano por artesanos de Oaxaca, utilizando técnicas tradicionales que han pasado de generación en generación. Desde los vibrantes alebrijes hasta la cerámica de barro policromado y la ropa bordada, nuestras creaciones cuentan historias de creatividad y tradición.
        </p>
    </section>
@endsection

@section('scripts')
    <script>
        // Animación suave para el enlace del hero
        document.querySelector('a[href="#productos"]').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('#productos').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
@endsection