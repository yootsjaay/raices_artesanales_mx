<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion; // Importa tu modelo Ubicacion
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index()
    {
        // CORREGIDO: Asignar el resultado a una variable para pasarla a la vista
        // La relación 'artesanias' está bien para precargar las artesanías de cada ubicación.
        $ubicaciones = Ubicacion::with('artesanias')->get();

        // Asegúrate de que 'ubicaciones.index' existe como archivo de vista
        return view('ubicaciones.index', compact('ubicaciones'));
    }

    public function show(Ubicacion $ubicacion)
    {
        // CORREGIDO: Eliminamos 'artesanias.artesano' ya que el modelo Artesano fue eliminado.
        // Ahora solo cargamos las artesanías y, para cada artesanía, su categoría.
        // La relación 'artesanias.categoria' es correcta porque Artesania sigue teniendo una categoría.
        $ubicacion->load(['artesanias.categoria']);

        // Asegúrate de que 'ubicaciones.show' existe como archivo de vista
        return view('ubicaciones.show', compact('ubicacion'));
    }
}