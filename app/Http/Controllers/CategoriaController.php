<?php

namespace App\Http\Controllers;

use App\Models\Categoria; // Importa tu modelo Categoria
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        // La carga de 'artesanias' aquí está bien.
        $categorias = Categoria::with('artesanias')->paginate(9);
        return view('categorias.index', compact('categorias'));
    }

    public function show(Categoria $categoria)
    {
        // CORREGIDO: Hemos quitado 'artesanias.artesano'
        // ya que la relación y el modelo Artesano ya no existen.
        // Si quisieras precargar la ubicación de las artesanías, podrías usar 'artesanias.ubicacion'.
        // Por ahora, solo cargamos las artesanías directamente.
        $categoria->load('artesanias');
        return view('categorias.show', compact('categoria'));
    }
}