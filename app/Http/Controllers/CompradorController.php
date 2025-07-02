<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CompradorController extends Controller
{
   public function dashboard()
{
    $user = Auth::user();
    return view('comprador.dashboard');
}

}
