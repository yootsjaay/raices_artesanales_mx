<?php

namespace App\Http\Controllers;

use App\Models\Artesania; // Importa tu modelo Artesania
use Illuminate\Http\Request;
use App\Services\MercadoPagoInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class ArtesaniaController extends Controller
{
  

 
    public function index()
    {
        // Obtiene todas las artesanías de la base de datos
        // CORREGIDO: Hemos quitado 'artesano' de la carga eagerly (with())
        // ya que la relación y el modelo Artesano ya no existen.
        $artesanias = Artesania::with(['categoria', 'ubicacion'])->get();

        // Pasa las artesanías a la vista 'artesanias.index'
        return view('artesanias.index', compact('artesanias'));
    }

    /**
     * Muestra los detalles de una artesanía específica.
     */
    public function show(Artesania $artesania) // Inyección de modelo: Laravel encuentra la artesanía por el ID en la URL
    {
        // Esto es crucial para que los comentarios aparezcan en la vista
      

        return view('artesanias.show', compact('artesania'));
    
    }
    
}