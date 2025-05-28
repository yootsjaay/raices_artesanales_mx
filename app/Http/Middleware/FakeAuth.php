<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FakeAuth
{
    public function handle($request, Closure $next)
    {
        // Solo si no hay usuario autenticado
        if (!Auth::check()) {
            // Crea uno temporal o busca un ID fijo
            $user = User::firstOrCreate([
                'email' => 'fake@demo.com'
            ], [
                'name' => 'Demo User',
                'password' => bcrypt('123456'),
            ]);

            Auth::login($user);
        }

        return $next($request);
    }
}