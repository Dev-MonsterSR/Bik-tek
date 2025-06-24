<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        if (session()->has('usuario_id')) {
            return redirect()->route('inicio');
        }
        if (session()->has('admin_id')) {
            return redirect()->route('admin.panel');
        }
        if (session()->has('trabajador_id')) {
            return redirect()->route('bibliotecario.dashboard');
        }

        return $next($request);
    }
}
