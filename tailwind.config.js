import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
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
        // --- Paleta de Colores de Oaxaca (Propuesta) ---

        // Fondos principales
        'oaxaca-bg-cream': '#FDF6E3', // Crema cálido para fondo principal
        'oaxaca-bg-white-off': '#F5F5F5', // Blanco roto (alternativa al crema si es necesario)

        // Navbar y Footer
        'oaxaca-navbar-blue': '#2A4B7C', // Azul profundo (añil) para navbar y footer
        'oaxaca-navbar-orange': '#F05A28', // Naranja quemado para hover en navbar, íconos o detalles

        // Encabezados y Títulos
        'oaxaca-title-pink': '#E91E63', // Rosa mexicano para títulos principales

        // Botones de Acción
        'oaxaca-button-mustard': '#F4A261', // Amarillo mostaza para botones de acción
        'oaxaca-button-mustard-hover': '#E28E4C', // Hover del amarillo mostaza

        // Fondos de secciones de productos
        'oaxaca-product-turquoise-light': '#E0F7FA', // Turquesa MUY suave para fondos de tarjetas (derivado de #26C6DA)
        'oaxaca-product-turquoise-dark': '#26C6DA', // Turquesa vibrante (para degradado o detalles)

        // Detalles y Bordes
        'oaxaca-detail-emerald': '#2ECC71', // Verde esmeralda para bordes o detalles

        // Texto
        'oaxaca-text-dark-gray': '#333333', // Gris oscuro para texto general
        'oaxaca-text-white': '#FFFFFF',     // Blanco para texto sobre fondos oscuros

        // --- Degradados (definidos con los nuevos colores) ---
        // Para el banner principal
        'gradient-start-pink': '#E91E63',
        'gradient-end-turquoise': '#26C6DA',

      },
      backgroundImage: {
        'oaxaca-hero-gradient': 'linear-gradient(to bottom, var(--tw-gradient-stops))',
      },
      gradientColorStops: theme => ({
        'gradient-start-pink': theme('colors.gradient-start-pink'),
        'gradient-end-turquoise': theme('colors.gradient-end-turquoise'),
      }),
      boxShadow: {
        'custom-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
      },
    },
  },
  plugins: [],
};  