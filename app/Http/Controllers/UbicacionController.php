<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion; // Importa tu modelo Ubicacion
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index()
    {
        $ubicaciones = Ubicacion::with(['artesanos', 'artesanias'])->get(); // Carga artesanos y artesanías de cada ubicación
        return view('ubicaciones.index', compact('ubicaciones'));
    }

    public function show(Ubicacion $ubicacion)
    {
        $ubicacion->load(['artesanos.artesanias', 'artesanias.artesano', 'artesanias.categoria']); // Carga relaciones anidadas
        return view('ubicaciones.show', compact('ubicacion'));
    }
}