<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CompradorController extends Controller
{
    public function dashboard()
    {
        return view('comprador.dashboard');
    }

}
