<?php

namespace App\Http\Controllers;

use App\Models\Artesania; // Importa tu modelo Artesania
use Illuminate\Http\Request;
use App\Services\MercadoPagoInterface;
class ArtesaniaController extends Controller
{
    protected $mp;
    public function __contruct(MercadoPagoInterface $mp){
        $this->mp= $mp;
        
    }

    public function pagarArtesanias($id){
        $artesania= Artesania::finOrFaild($id);

        $datosOrden= [
            'referencia' => 'ART'.$artesania->$id . '-' . $time(),
            'titulo'=> $artesania->nombre,
            'precio'=> $artesania->precio,
            'email' => Auth()->user()->email,
        ];

        $orden = $this->mp->crearOrden($datosOrden);

        return response()->json([
            'mensaje'=> 'orden Creada',
            'datos'=> $orden,
        ]);
    }
    /**
     * Muestra una lista de todas las artesanías.
     */
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
        $artesania->load(['comments' => function ($query) {
            $query->where('status', 'approved')->with('user')->latest();
        }]);

        return view('artesanias.show', compact('artesania'));
    
    }
}