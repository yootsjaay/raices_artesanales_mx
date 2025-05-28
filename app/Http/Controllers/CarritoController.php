<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artesania;


class CarritoController extends Controller
{
    //
    public function agregar(Request $request)
{
    $request->validate([
        'artesania_id' => 'required|exists:artesanias,id',
        'cantidad' => 'required|integer|min:1',
    ]);

    $artesania = Artesania::findOrFail($request->artesania_id);

    $carrito = session()->get('carrito', []);

    if (isset($carrito[$artesania->id])) {
        $carrito[$artesania->id]['cantidad'] += $request->cantidad;
    } else {
        $carrito[$artesania->id] = [
            'nombre' => $artesania->nombre,
            'precio' => $artesania->precio,
            'cantidad' => $request->cantidad,
            'imagen' => $artesania->imagen_principal,
        ];
    }

    session()->put('carrito', $carrito);

    return redirect()->back()->with('success', 'Artesanía añadida al carrito.');
}
public function mostrar()
{
    $carrito = session()->get('carrito', []);
    $total = 0;

    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    return view('carrito.index', compact('carrito', 'total'));
}
public function remover(Request $request)
{
    $carrito = session()->get('carrito', []);

    if (isset($carrito[$request->id])) {
        unset($carrito[$request->id]);
        session()->put('carrito', $carrito);
    }

    return redirect()->route('carrito.mostrar')->with('success', 'Producto eliminado del carrito.');
}
public function vaciar()
{
    session()->forget('carrito');
    return redirect()->route('carrito.mostrar')->with('success', 'Carrito vaciado.');
}

}
