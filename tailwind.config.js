import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // PALETA PRINCIPAL 'RAÍCES ARTESANALES MX' - INSPIRADA EN OAXACA
                'oaxaca-primary': '#4A2328',     // Terracota Oscuro: Evoca el barro rojo de San Marcos Tlapazola o los tonos profundos de los textiles. Ideal para fondos, textos principales y elementos sólidos.
                'oaxaca-secondary': '#A63D40',   // Rojo Grana Cochineal: Un rojo vibrante, que recuerda el tinte natural de la cochinilla usado en tapetes de Teotitlán. Excelente para acentos, botones de llamada a la acción y hovers.
                'oaxaca-tertiary': '#F0A100',    // Amarillo Cempasúchil/Maíz: Un amarillo cálido y brillante, como las flores de Día de Muertos o el maíz de las tortillas. Perfecto para precios, highlights y elementos que necesiten destacar.
                'oaxaca-accent': '#2C5F2D',      // Verde Agave/Jade: Un verde profundo y terroso, como los campos de agave o el jade de las piezas prehispánicas. Úsalo para enlaces, íconos o pequeños detalles.

                // COLORES DE TEXTO Y FONDO BASE (complementarios)
                'oaxaca-text-dark': '#1A1A1A',    // Negro Obsidiana: Para texto principal, ofreciendo alto contraste y legibilidad.
                'oaxaca-text-light': '#F8F8F8',   // Crema de Maguey: Un blanco roto, suave, ideal para texto sobre fondos oscuros.
                'oaxaca-bg-light': '#FCF8F5',    // Arena de Valle: Un fondo muy claro, casi blanco, que simula la tierra limpia y cálida de los valles centrales.
                'oaxaca-card-bg': '#FEFCF0',     // Papel Amate Claro: Un tono ligeramente más cálido que 'bg-light', para dar una sensación de textura natural a las tarjetas y secciones.
            },
            fontFamily: {
                'sans': ['Nunito Sans', ...defaultTheme.fontFamily.sans],
                'display': ['Cormorant Garamond', 'serif'],
            },
            boxShadow: {
                // Mantén estos si los estás usando activamente, o modifícalos para reflejar la nueva paleta
                'custom-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                // Sombra sutil que podría recordar los pigmentos de tierra o un acabado artesanal
                'artisanal': '0 4px 12px rgba(74, 35, 40, 0.15)', // Usando oaxaca-primary para la sombra
            },
        },
    },
    plugins: [
        forms,
        typography,
    ],
};