<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
        public function create(): View
    {
        if (request()->has('redirect')) {
            session(['url.intended' => request('redirect')]);
        }

        return view('auth.login');
    }


    /**
     * Handle an incoming authentication request.
     */
 public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    // 👉 Si hay una URL previa (como /carrito), redirige ahí
    if (session()->has('url.intended')) {
        return redirect()->intended();
    }

    // 👉 Si no, redirige por rol
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('vendedor')) {
        return redirect()->route('vendedor.dashboard');
    }

    if ($user->hasRole('comprador')) {
        return redirect()->route('comprador.dashboard');
    }

    return redirect('/');
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
