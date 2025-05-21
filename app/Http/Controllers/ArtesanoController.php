<?php

namespace App\Http\Controllers;

use App\Models\Artesano; // Importa tu modelo Artesano
use Illuminate\Http\Request;

class ArtesanoController extends Controller
{
    public function index()
    {
        $artesanos = Artesano::with('ubicacion')->get(); // Carga también la ubicación
        return view('artesanos.index', compact('artesanos'));
    }

     public function show(Artesano $artesano)
    {
        $artesano->load(['ubicacion', 'artesanias.categoria']); // Carga ubicación, y artesanías con sus categorías
        return view('artesanos.show', compact('artesano'));
    }
}