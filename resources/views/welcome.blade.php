@extends('layouts.public')

@section('title', 'Raíces Artesanales MX - Tesoros de Oaxaca')

@section('content')
    {{-- SECCIÓN HERO - Banner Principal --}}
    <header class="bg-oaxaca-primary text-oaxaca-text-light text-center py-16 md:py-24 px-4 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-5xl md:text-6xl font-display font-bold mb-4 drop-shadow-md">Descubre el Alma de Oaxaca</h1>
            <p class="text-xl md:text-2xl mb-8 leading-relaxed font-sans text-oaxaca-text-light text-opacity-90">
                Artesanías únicas hechas a mano que cuentan historias de tradición, pasión y color.
            </p>
            <a href="#artesanias-destacadas" class="bg-oaxaca-tertiary text-oaxaca-primary px-8 py-4 rounded-full font-semibold text-lg hover:bg-oaxaca-secondary hover:text-white transition-colors shadow-lg transform hover:scale-105">
                Explora Nuestra Colección
            </a>
        </div>
    </header>

    {{-- SECCIÓN: ARTESANÍAS DESTACADAS --}}
    <section id="artesanias-destacadas" class="py-16 px-4 bg-oaxaca-bg-light mx-auto max-w-7xl">
        <h2 class="text-4xl md:text-5xl font-display font-bold text-center text-oaxaca-primary mb-12">
            Nuestras Joyas Artesanales
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- TARJETA DE ALEBRIJES --}}
            <div class="bg-oaxaca-card-bg rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-oaxaca-primary border-opacity-10">
                <img src="{{ asset('storage/images/placeholder/alebrije-placeholder.jpg') }}" alt="Alebrije Oaxaqueño" class="w-full h-64 object-cover object-center">
                <div class="p-6">
                    <h3 class="text-2xl font-display font-semibold text-oaxaca-primary mb-2">Alebrijes</h3>
                    <p class="text-oaxaca-text-dark text-base leading-relaxed mb-4">
                        Figuras fantásticas talladas y pintadas a mano con vibrantes colores que capturan el espíritu de los sueños y la imaginación.
                    </p>
                    <p class="text-oaxaca-tertiary font-bold text-xl mb-4">$500 - $5,000 MXN</p>
                    <a href="{{ route('categorias.show', 1) }}" class="mt-auto inline-block w-full bg-oaxaca-tertiary text-oaxaca-primary px-6 py-3 rounded-lg hover:bg-oaxaca-secondary hover:text-white transition-colors text-center text-lg font-semibold shadow-md">
                        Ver Más
                    </a>
                </div>
            </div>

            {{-- TARJETA DE BARROS --}}
            <div class="bg-oaxaca-card-bg rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-oaxaca-primary border-opacity-10">
                <img src="{{ asset('storage/images/placeholder/barro-rojo.jpg') }}" alt="Cerámica de Barro Oaxaqueño" class="w-full h-64 object-cover object-center">
                <div class="p-6">
                    <h3 class="text-2xl font-display font-semibold text-oaxaca-primary mb-2">Barros</h3>
                    <p class="text-oaxaca-text-dark text-base leading-relaxed mb-4">
                        Desde el elegante barro negro de San Bartolo Coyotepec hasta piezas policromadas, cada vasija narra una historia.
                    </p>
                    <p class="text-oaxaca-tertiary font-bold text-xl mb-4">$200 - $2,000 MXN</p>
                    <a href="{{ route('categorias.show', 3) }}" class="mt-auto inline-block w-full bg-oaxaca-tertiary text-oaxaca-primary px-6 py-3 rounded-lg hover:bg-oaxaca-secondary hover:text-white transition-colors text-center text-lg font-semibold shadow-md">
                        Ver Más
                    </a>
                </div>
            </div>

            {{-- TARJETA DE TEXTILES --}}
            <div class="bg-oaxaca-card-bg rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-oaxaca-primary border-opacity-10">
                <img src="{{ asset('storage/images/placeholder/ropa-tradicional.jpg') }}" alt="Textiles Artesanales de Oaxaca" class="w-full h-64 object-cover object-center">
                <div class="p-6">
                    <h3 class="text-2xl font-display font-semibold text-oaxaca-primary mb-2">Textiles</h3>
                    <p class="text-oaxaca-text-dark text-base leading-relaxed mb-4">
                        Huipiles, rebozos y prendas únicas bordadas a mano con diseños ancestrales, un verdadero arte que se viste.
                    </p>
                    <p class="text-oaxaca-tertiary font-bold text-xl mb-4">$800 - $3,000 MXN</p>
                    <a href="{{ route('categorias.show', 4) }}" class="mt-auto inline-block w-full bg-oaxaca-tertiary text-oaxaca-primary px-6 py-3 rounded-lg hover:bg-oaxaca-secondary hover:text-white transition-colors text-center text-lg font-semibold shadow-md">
                        Ver Más
                    </a>
                </div>
            </div>
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('artesanias.index') }}" class="inline-block bg-oaxaca-accent text-white px-10 py-4 rounded-full font-semibold text-lg hover:bg-oaxaca-primary transition-colors shadow-md transform hover:scale-105">
                Ver Todo el Catálogo
            </a>
        </div>
    </section>

    {{-- SECCIÓN: SOBRE NOSOTROS --}}
    <section class="bg-oaxaca-primary text-oaxaca-text-light py-16 px-4 mt-12">
        <div class="container mx-auto max-w-4xl">
            <h2 class="text-4xl md:text-5xl font-display font-bold text-center text-oaxaca-tertiary mb-8">
                Nuestra Historia, Nuestra Pasión
            </h2>
            <p class="text-lg font-sans text-oaxaca-text-light text-opacity-90 max-w-3xl mx-auto text-center leading-relaxed mb-8">
                En **Raíces Artesanales MX**, celebramos la invaluable riqueza cultural de Oaxaca, México. Cada pieza en nuestra colección es el resultado del trabajo y dedicación de talentosos artesanos locales, quienes emplean técnicas ancestrales transmitidas de generación en generación.
            </p>
            <p class="text-lg font-sans text-oaxaca-text-light text-opacity-90 max-w-3xl mx-auto text-center leading-relaxed">
                Nuestra misión es conectar el arte oaxaqueño con el mundo, asegurando que cada compra no solo adquiera un objeto bello, sino que también apoye directamente a las comunidades que mantienen viva esta tradición. Desde los vibrantes Alebrijes que danzan con la imaginación, hasta el refinado Barro Negro y los intrincados Textiles bordados a mano, cada creación es una historia viva de Oaxaca.
            </p>
            <div class="text-center mt-10">
                <a href="#" class="inline-block bg-oaxaca-tertiary text-oaxaca-primary px-8 py-3 rounded-full hover:bg-oaxaca-secondary hover:text-white transition-colors text-lg font-medium shadow-md">
                    Conoce a Nuestros Artesanos
                </a>
            </div>
        </div>
    </section>

    {{-- SECCIÓN: TESTIMONIOS/VALORES (Opcional, para futura expansión) --}}
    {{-- <section class="bg-oaxaca-bg-light py-12 px-4 mt-8">
        <div class="container mx-auto">
            <h2 class="text-4xl font-display font-bold text-center text-oaxaca-primary mb-10">Lo que Dicen Nuestros Clientes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md border border-oaxaca-accent border-opacity-20">
                    <p class="text-lg text-oaxaca-text-dark italic mb-4">"Absolutamente enamorada de mi alebrije. La calidad es increíble y el envío fue muy rápido. ¡Una pieza de arte en mi casa!"</p>
                    <p class="font-semibold text-oaxaca-primary">- Sofía R.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border border-oaxaca-accent border-opacity-20">
                    <p class="text-lg text-oaxaca-text-dark italic mb-4">"Los textiles son una maravilla. Se nota el trabajo artesanal y la pasión en cada puntada. ¡Volveré a comprar sin duda!"</p>
                    <p class="font-semibold text-oaxaca-primary">- Carlos M.</p>
                </div>
            </div>
        </div>
    </section> --}}
@endsection

@section('scripts')
    <script>
        // Animación suave para el enlace del hero
        document.querySelector('a[href="#artesanias-destacadas"]').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('#artesanias-destacadas').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
@endsection