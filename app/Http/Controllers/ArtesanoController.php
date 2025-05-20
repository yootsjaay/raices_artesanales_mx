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
        $artesano->load(['artesanias', 'ubicacion']); // Carga sus artesanías y su ubicación
        return view('artesanos.show', compact('artesano'));
    }
}