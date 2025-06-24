<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = session('rol');

        if ($role === 'bibliotecario' && $userRole !== 'trabajador') {
            abort(403, 'No tiene permiso para acceder a esta sección.');
        }

        if ($role === 'admin' && $userRole !== 'admin') {
            abort(403, 'No tiene permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
