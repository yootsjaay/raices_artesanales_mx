<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EnviaService;

class EnviaController extends Controller
{
    public function formulario()
    {
        return view('envia.quote_form');
    }

    public function cotizar(Request $request)
    {
        $data = $request->all(); // puedes validar si gustas
        $envia = new EnviaService();
        $cotizacion = $envia->cotizar($data);

        return view('envia.quote_form', compact('cotizacion'));
    }
}
