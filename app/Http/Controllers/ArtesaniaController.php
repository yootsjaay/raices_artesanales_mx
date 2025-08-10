<?php

namespace App\Http\Controllers;

use App\Models\Artesania;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Ubicacion;


class ArtesaniaController extends Controller
{
    
    public function index(Request $request)
    {
        $categorias = Categoria::all();
        $ubicaciones = Ubicacion::all();

        $query = Artesania::with(['categoria', 'ubicacion', 'variants'])
                         ->where('is_active', true);

        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->has('ubicacion_id') && $request->ubicacion_id != '') {
            $query->where('ubicacion_id', $request->ubicacion_id);
        }

        $artesanias = $query->paginate(12);

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
            'variants' => fn ($query) => $query->where('is_active', true),
            'comments.user',
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        // Calcular el stock total sumando el stock de todas las variantes activas
        $totalStock = $artesania->variants->sum('stock');

        // La lógica para la variante inicial se mueve a la vista, que es más flexible
        $selectedVariant = $artesania->variants->firstWhere('stock', '>', 0) ?: null;

        return view('artesanias.show', compact('artesania', 'totalStock', 'selectedVariant'));
    }
}