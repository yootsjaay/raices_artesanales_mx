<?php

namespace App\Http\Controllers;

use App\Models\Artesania; // Importa tu modelo Artesania
use Illuminate\Http\Request;

class ArtesaniaController extends Controller
{
    /**
     * Muestra una lista de todas las artesanías.
     */
    public function index()
    {
        // Obtiene todas las artesanías de la base de datos
        // Con with(), cargamos las relaciones (artesano, categoria, ubicacion)
        // para evitar consultas N+1 y optimizar el rendimiento.
        $artesanias = Artesania::with(['artesano', 'categoria', 'ubicacion'])->get();

        // Pasa las artesanías a la vista 'artesanias.index'
        return view('artesanias.index', compact('artesanias'));
    }

    /**
     * Muestra los detalles de una artesanía específica.
     */
    public function show(Artesania $artesania) // Inyección de modelo: Laravel encuentra la artesanía por el ID en la URL
    {
        // La artesanía ya viene cargada por la inyección de modelo.
        // Si necesitas cargar relaciones específicas aquí, puedes hacerlo:
        $artesania->load(['artesano', 'categoria', 'ubicacion']);

        // Pasa la artesanía a la vista 'artesanias.show'
        return view('artesanias.show', compact('artesania'));
    }
}