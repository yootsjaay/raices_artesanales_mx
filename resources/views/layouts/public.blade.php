<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Raíces Artesanales MX - Tesoros de Oaxaca')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Nunito+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Lightbox2 CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="font-sans antialiased text-oaxaca-text-dark bg-oaxaca-bg-light bg-texture bg-repeat bg-opacity-30">

    {{-- BARRA SUPERIOR --}}
    <div class="bg-oaxaca-primary text-oaxaca-text-light py-2 px-4 text-sm">
        <div class="container mx-auto flex justify-between items-center">
            <span>Envíos a todo México | Artesanías 100% auténticas</span>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-oaxaca-secondary transition-colors">Seguimiento de pedidos</a>
                <a href="#" class="hover:text-oaxaca-secondary transition-colors">Ayuda</a>
            </div>
        </div>
    </div>

    {{-- HEADER PRINCIPAL (con Navbar y Búsqueda integrada) --}}
    <header class="sticky top-0 z-50 bg-white bg-opacity-90 backdrop-blur-sm shadow-md">
        <div class="container mx-auto px-4 py-3 flex flex-col md:flex-row justify-between items-center">
            {{-- LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center mb-4 md:mb-0">
                {{-- Aquí puedes colocar un SVG o un img src real para tu logo --}}
                <div class="bg-oaxaca-accent rounded-xl w-16 h-16 flex items-center justify-center text-white text-3xl font-bold">R</div>
                <div class="ml-3">
                    <span class="font-display text-3xl font-bold text-oaxaca-primary">Raíces</span>
                    <span class="block font-sans text-xs uppercase tracking-widest text-oaxaca-accent">Artesanías Oaxaqueñas</span>
                </div>
            </a>

            {{-- MENÚ PRINCIPAL Y BÚSQUEDA (Centrado en desktop, apilado en móvil) --}}
            <div class="flex-1 flex flex-col md:flex-row items-center justify-center w-full md:w-auto">
                <ul class="flex flex-wrap justify-center space-x-1 md:space-x-6 text-lg text-oaxaca-text-dark mb-4 md:mb-0">
                    <li>
                        <a href="{{ route('artesanias.index') }}"
                           class="px-4 py-2 rounded-lg hover:bg-oaxaca-secondary hover:bg-opacity-20 transition-all duration-300">
                           Artesanías
                        </a>
                    </li>
                    {{-- Menú Desplegable "Explorar" --}}
                    <li class="relative group">
                        <button class="px-4 py-2 rounded-lg hover:bg-oaxaca-secondary hover:bg-opacity-20 transition-all duration-300 flex items-center focus:outline-none" id="dropdown-explore-button">
                            Explorar <span class="ml-2 text-sm">&#9662;</span>
                        </button>
                        <ul id="dropdown-explore-menu" class="absolute hidden bg-white rounded-md shadow-lg py-2 w-48 text-oaxaca-text-dark z-20 group-hover:block border border-oaxaca-primary border-opacity-10">
                            <li><a href="{{ route('categorias.index') }}" class="block px-4 py-2 hover:bg-oaxaca-bg-light hover:text-oaxaca-primary transition-colors">Categorías</a></li>
                            <li><a href="{{ route('ubicaciones.index') }}" class="block px-4 py-2 hover:bg-oaxaca-bg-light hover:text-oaxaca-primary transition-colors">Ubicaciones</a></li>
                            <li><a href="#" class="block px-4 py-2 hover:bg-oaxaca-bg-light hover:text-oaxaca-primary transition-colors">Artesanos</a></li>
                            <li><a href="#" class="block px-4 py-2 hover:bg-oaxaca-bg-light hover:text-oaxaca-primary transition-colors">Nuestra Historia</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"
                           class="px-4 py-2 rounded-lg hover:bg-oaxaca-secondary hover:bg-opacity-20 transition-all duration-300">
                           Contacto
                        </a>
                    </li>
                </ul>

                {{-- BARRA DE BÚSQUEDA (Integrada en el header) --}}
                <div class="w-full md:w-1/3 md:ml-6">
                    <form class="relative">
                        <input type="text" placeholder="Buscar..." class="w-full pl-10 pr-4 py-2 rounded-full border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-accent text-oaxaca-text-dark text-sm">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-oaxaca-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </form>
                </div>
            </div>

            {{-- ICONOS USUARIO/CARRITO --}}
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <a href="{{ route('carrito.mostrar') }}" class="relative p-2 hover:text-oaxaca-primary transition-colors">
                    <svg class="w-6 h-6 text-oaxaca-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-oaxaca-primary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        {{-- Aquí iría la lógica para el contador del carrito, e.g., \Cart::count() --}}
                        3
                    </span>
                </a>
                <a href="{{ route('login') }}" class="bg-oaxaca-tertiary text-white px-4 py-2 rounded-lg flex items-center hover:bg-oaxaca-primary transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Ingresar
                </a>
            </div>
        </div>
    </header>

    <main class="py-6">
        @yield('content')
    </main>

    {{-- NEWSLETTER --}}
    <section class="py-10 bg-oaxaca-card-bg border-y border-oaxaca-accent border-opacity-20">
        <div class="container mx-auto px-4 text-center max-w-3xl">
            <h3 class="font-display text-3xl text-oaxaca-primary mb-2">Únete a nuestra comunidad</h3>
            <p class="text-oaxaca-text-dark mb-6">Recibe promociones exclusivas y conoce las nuevas artesanías</p>

            <form class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
                <input type="email" placeholder="Tu correo electrónico" class="flex-grow px-4 py-3 rounded-lg border border-oaxaca-accent border-opacity-30 focus:outline-none focus:ring-1 focus:ring-oaxaca-accent">
                <button type="submit" class="bg-oaxaca-primary text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition-colors font-medium">
                    Suscribirme
                </button>
            </form>
        </div>
    </section>

    {{-- FOOTER MEJORADO --}}
    <footer class="bg-oaxaca-primary text-oaxaca-text-light">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- COLUMNA 1: LOGO Y DESCRIPCIÓN --}}
                <div>
                    <div class="flex items-center mb-4">
                        {{-- Aquí puedes colocar un SVG o un img src real para tu logo --}}
                        <div class="bg-oaxaca-accent rounded-xl w-12 h-12 flex items-center justify-center text-white text-2xl font-bold">R</div>
                        <span class="ml-3 font-display text-2xl">Raíces</span>
                    </div>
                    <p class="text-oaxaca-text-light text-opacity-80 mb-4 text-sm">
                        Preservando y promoviendo las tradiciones artesanales de Oaxaca desde 2010.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-oaxaca-secondary hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="text-oaxaca-secondary hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                        </a>
                        <a href="#" class="text-oaxaca-secondary hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                    </div>
                </div>

                {{-- COLUMNA 2: ENLACES RÁPIDOS --}}
                <div>
                    <h4 class="font-display text-xl mb-4 border-b border-oaxaca-secondary border-opacity-30 pb-2">Explorar</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('artesanias.index') }}" class="hover:text-oaxaca-secondary transition-colors">Artesanías</a></li>
                        <li><a href="" class="hover:text-oaxaca-secondary transition-colors">Artesanos</a></li>
                        <li><a href="{{ route('categorias.index') }}" class="hover:text-oaxaca-secondary transition-colors">Categorías</a></li>
                        <li><a href="{{ route('ubicaciones.index') }}" class="hover:text-oaxaca-secondary transition-colors">Ubicaciones</a></li>
                        <li><a href="#" class="hover:text-oaxaca-secondary transition-colors">Nuestra Historia</a></li>
                    </ul>
                </div>

                {{-- COLUMNA 3: AYUDA --}}
                <div>
                    <h4 class="font-display text-xl mb-4 border-b border-oaxaca-secondary border-opacity-30 pb-2">Ayuda</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-oaxaca-secondary transition-colors">Preguntas Frecuentes</a></li>
                        <li><a href="#" class="hover:text-oaxaca-secondary transition-colors">Envíos y Entregas</a></li>
                        <li><a href="#" class="hover:text-oaxaca-secondary transition-colors">Devoluciones</a></li>
                        <li><a href="#" class="hover:text-oaxaca-secondary transition-colors">Guía de Tallas</a></li>
                        <li><a href="#" class="hover:text-oaxaca-secondary transition-colors">Contacto</a></li>
                    </ul>
                </div>

                {{-- COLUMNA 4: CONTACTO --}}
                <div>
                    <h4 class="font-display text-xl mb-4 border-b border-oaxaca-secondary border-opacity-30 pb-2">Contacto</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-oaxaca-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +52 951 453 7503
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-oaxaca-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            raices@artesanales.mx
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-oaxaca-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Calle Humboldt 104, Centro<br>Oaxaca de Juárez, Oaxaca
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-oaxaca-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Lunes a Sábado: 10:00 - 20:00<br>Domingo: 11:00 - 18:00
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- COPYRIGHT --}}
        <div class="border-t border-oaxaca-secondary border-opacity-20 py-6 text-center text-sm text-oaxaca-text-light text-opacity-70">
            <div class="container mx-auto px-4">
                &copy; {{ date('Y') }} Raíces Artesanales MX. Todos los derechos reservados.
                <a href="#" class="hover:text-oaxaca-secondary transition-colors">Términos y Condiciones</a> |
                <a href="#" class="hover:text-oaxaca-secondary transition-colors">Política de Privacidad</a>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Artesanía %1 de %2'
        });

        // Fijar header al hacer scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            header.classList.toggle('shadow-lg', window.scrollY > 10);
        });

        // Lógica para el menú desplegable "Explorar"
        const dropdownExploreButton = document.getElementById('dropdown-explore-button');
        const dropdownExploreMenu = document.getElementById('dropdown-explore-menu');

        if (dropdownExploreButton && dropdownExploreMenu) {
            dropdownExploreButton.addEventListener('click', () => {
                dropdownExploreMenu.classList.toggle('hidden');
            });

            // Cierra el menú si se hace clic fuera de él
            document.addEventListener('click', (event) => {
                if (!dropdownExploreButton.contains(event.target) && !dropdownExploreMenu.contains(event.target)) {
                    dropdownExploreMenu.classList.add('hidden');
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>