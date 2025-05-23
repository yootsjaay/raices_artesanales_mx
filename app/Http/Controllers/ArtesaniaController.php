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
        // CORREGIDO: Hemos quitado 'artesano' de la carga eagerly (with())
        // ya que la relación y el modelo Artesano ya no existen.
        $artesanias = Artesania::with(['categoria', 'ubicacion'])->get();

        // Pasa las artesanías a la vista 'artesanias.index'
        return view('artesanias.index', compact('artesanias'));
    }

    /**
     * Muestra los detalles de una artesanía específica.
     */
    public function show(Artesania $artesania) // Inyección de modelo: Laravel encuentra la artesanía por el ID en la URL
    {
        // La artesanía ya viene cargada por la inyección de modelo.
        // CORREGIDO: Hemos quitado 'artesano' de la carga perezosa (load())
        // ya que la relación y el modelo Artesano ya no existen.
        $artesania->load(['categoria', 'ubicacion']);

        // Pasa la artesanía a la vista 'artesanias.show'
        return view('artesanias.show', compact('artesania'));
    }
}