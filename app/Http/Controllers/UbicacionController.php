<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion; // Importa tu modelo Ubicacion
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index()
    {
        // CORREGIDO: Asignar el resultado a una variable para pasarla a la vista
        $ubicaciones = Ubicacion::with('artesanos')->get();

        // Asegúrate de que 'ubicaciones.index' existe como archivo de vista
        return view('ubicaciones.index', compact('ubicaciones'));
    }

    public function show(Ubicacion $ubicacion)
    {
        // CORREGIDO: Asegurarse de que las relaciones anidadas existan en sus respectivos modelos
        // Por ejemplo, 'artesanos.artesanias' significa que el modelo Artesano debe tener una relación 'artesanias()'
        // y 'artesanias.artesano' significa que el modelo Artesania debe tener una relación 'artesano()'
        // y 'artesanias.categoria' significa que el modelo Artesania debe tener una relación 'categoria()'

        $ubicacion->load(['artesanos.artesanias', 'artesanias.artesano', 'artesanias.categoria']);
        // ^ Este `load` parece correcto asumiendo que las relaciones anidadas también existen.

        // Asegúrate de que 'ubicaciones.show' existe como archivo de vista
        return view('ubicaciones.show', compact('ubicacion'));
    }
}