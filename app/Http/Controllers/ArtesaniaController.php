<?php

namespace App\Http\Controllers;

use App\Models\Artesania;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Ubicacion;
// No es necesario importar ArtesaniaVariant, Storage, TipoEmbalaje, AtributoArtesaniaVariant aquí si solo se usan en los modelos o en otras partes del sistema
// use App\Models\ArtesaniaVariant;
// use Illuminate\Support\Facades\Storage;
// use App\Models\TipoEmbalaje;
// use App\Models\AtributoArtesaniaVariant;

class ArtesaniaController extends Controller
{
    /**
     * Muestra una lista paginada de todas las artesanías.
     * Permite filtrar por categoría y ubicación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Obtener todas las categorías y ubicaciones para el filtro
        $categorias = Categoria::all();
        $ubicaciones = Ubicacion::all();

        // Iniciar la query para las artesanías.
        $query = Artesania::with(['categoria', 'ubicacion']);

        // Filtrar por categoría si se proporciona
        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtrar por ubicación si se proporciona
        if ($request->has('ubicacion_id') && $request->ubicacion_id != '') {
            $query->where('ubicacion_id', $request->ubicacion_id);
        }

        // Obtener las artesanías paginadas
        $artesanias = $query->paginate(12);

        // Devolver la vista con los datos
        return view('artesanias.index', compact('artesanias', 'categorias', 'ubicaciones'));
    }

    /**
     * Muestra una artesanía específica con sus variantes y detalles.
     *
     * @param  string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug)
{
    $artesania = Artesania::with([
        'categoria',
        'ubicacion',
        'variants', // Relación corregida
        'comments.user',
    ])->where('slug', $slug)->firstOrFail();

    // Seleccionar primera variante CON STOCK si es posible
    $selectedVariant = $artesania->variants
        ->where('stock', '>', 0)
        ->first() ?: $artesania->variants->first();

    return view('artesanias.show', compact('artesania', 'selectedVariant'));
}
}