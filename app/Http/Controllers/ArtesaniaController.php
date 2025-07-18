<?php

namespace App\Http\Controllers;

use App\Models\Artesania; // Importa tu modelo Artesania
use Illuminate\Http\Request;
use App\Services\MercadoPagoInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ArtesaniaVariant;
class ArtesaniaController extends Controller
{
  
    public function index()
    {
        // Obtiene todas las artesanías de la base de datos
        // CORREGIDO: Hemos quitado 'artesano' de la carga eagerly (with())
        // ya que la relación y el modelo Artesano ya no existen.
        $artesanias = Artesania::with(['categoria', 'ubicacion'])->paginate(15);

        // Pasa las artesanías a la vista 'artesanias.index'
        return view('artesanias.index', compact('artesanias'));
    }

    /**
     * Muestra los detalles de una artesanía específica.
     */
   public function show($slug)
    {
        $artesania = Artesania::with(['categoria', 'ubicacion', 'artesania_variants'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Inicializamos $selectedVariant a null. Esto asegura que la variable siempre esté definida.
        $selectedVariant = null;

        // 1. Intentar obtener la variante por ID de la URL si se proporciona
        if (request()->filled('variant')) {
            $selectedVariant = ArtesaniaVariant::where('id', request('variant'))
                ->where('artesania_id', $artesania->id)
                ->first();
        }

        // 2. Si no se encontró una variante válida por ID, buscar la variante principal
        // Asumimos que 'is_main' es true para la variante principal.
        // Si tu lógica es que 'is_main' es false para la principal, ajusta aquí.
        if (!$selectedVariant) {
            $selectedVariant = $artesania->artesania_variants->where('is_main', false)->first();
        }

        // 3. Si aún no hay variante (ej. no hay principal o 'is_main' no se usa), tomar la primera disponible
        // Esto es una opción de fallback para asegurar que siempre haya una variante seleccionada si existen.
        if (!$selectedVariant && $artesania->artesania_variants->isNotEmpty()) {
            $selectedVariant = $artesania->artesania_variants->first();
        }

        // Pasamos tanto la artesanía como la variante seleccionada (que puede ser null si no hay variantes)
        return view('artesanias.show', compact('artesania', 'selectedVariant'));
    }


   


   
}