<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Models\State;

class AddressController extends Controller
{
    // Mostrar todas las direcciones del usuario autenticado
    public function index()
    {
        $addresses = Auth::user()->addresses;
        return view('profile.addresses.index', compact('addresses'));
    }

    // Mostrar formulario para crear una nueva dirección
public function create()
{
    $states = State::orderBy('name')->get();

    return view('profile.addresses.create', compact('states'));
}


    // Guardar nueva dirección en base de datos
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email',
        'phone' => 'required|string|max:20',
        'street' => 'required|string|max:255',
        'number' => 'required|string|max:10',
        'district' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|size:2',  // tamaño exacto 2 para abreviación
        'postal_code' => 'required|string|max:10',
        'country' => 'nullable|string|size:2', // si decides que no sea obligatorio
        'reference' => 'nullable|string|max:255',
    ]);

    $data = $request->only([
        'name', 'email', 'phone', 'street', 'number',
        'district', 'city', 'state', 'postal_code',
        'country', 'reference'
    ]);

    // Si country no viene, lo pones por default México
    $data['country'] = $data['country'] ?? 'MX';

    Auth::user()->addresses()->create($data);

    return redirect()->route('profile.addresses.index')->with('success', 'Dirección guardada correctamente.');
}
   public function edit(Address $address)
{
    if ($address->user_id !== auth()->id()) {
        abort(403, 'No tienes permiso para editar esta dirección.');
    }

    $states = State::orderBy('name')->get();
    return view('profile.addresses.edit', compact('address', 'states'));
}


    // Actualizar dirección en base de datos
   public function update(Request $request, Address $address)
{
    if ($address->user_id !== auth()->id()) {
        abort(403, 'No tienes permiso para modificar esta dirección.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email',
        'phone' => 'required|string|max:20',
        'street' => 'required|string|max:255',
        'number' => 'required|string|max:10',
        'district' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|size:2',
        'postal_code' => 'required|string|max:10',
        'country' => 'nullable|string|size:2',
        'reference' => 'nullable|string|max:255',
    ]);

    $data = $request->only([
        'name', 'email', 'phone', 'street', 'number',
        'district', 'city', 'state', 'postal_code',
        'country', 'reference'
    ]);

    $data['country'] = $data['country'] ?? 'MX';

    $address->update($data);

    return redirect()->route('profile.addresses.index')->with('success', 'Dirección actualizada.');
}


    // Eliminar dirección
  public function destroy(Address $address)
{
    // Verifica que la dirección sea del usuario actual
    if ($address->user_id !== auth()->id()) {
        abort(403, 'No tienes permiso para eliminar esta dirección.');
    }

    $address->delete();

    return redirect()->route('profile.addresses.index')->with('success', 'Dirección eliminada.');
}

}
