<?php

namespace App\Http\Controllers;

use App\Models\Categoria; // Importa tu modelo Categoria
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with('artesanias')->paginate(9);
        return view('categorias.index', compact('categorias'));
    }

    public function show(Categoria $categoria)
    {
        $categoria->load('artesanias.artesano'); // Carga las artesanías y el artesano de cada una
        return view('categorias.show', compact('categoria'));
    }
}