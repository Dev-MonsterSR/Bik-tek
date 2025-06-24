<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Sancion;
use App\Models\Prestamo;
use Carbon\Carbon;

class VerificarPrestamosYSanciones
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si hay sesión de usuario (no admin ni trabajador)
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            // Si no hay sesión de usuario, redirige al login
            return redirect()->route('login')->with('error', 'Debes iniciar sesión como usuario para acceder.');
        }

        // Obtiene el usuario autenticado
        $usuario = Usuario::find($usuarioId);
        if (!$usuario) {
            session()->forget('usuario_id');
            return redirect()->route('login')->with('error', 'Usuario no encontrado.');
        }

        // Verifica si el usuario tiene sanciones activas
        $sancionActiva = Sancion::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'activa')
            ->where(function($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', Carbon::now());
            })
            ->first();

        if ($sancionActiva) {
            $fechaFin = $sancionActiva->fecha_fin ? Carbon::parse($sancionActiva->fecha_fin)->format('d/m/Y') : 'indefinida';
            return redirect()->route('usuario.historial')->with('error',
                'Tienes una sanción activa hasta ' . $fechaFin . '. No puedes realizar préstamos.');
        }

        // Regla: No permitir más de 3 préstamos activos
        $prestamosActivos = Prestamo::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'activo')
            ->count();
        if ($prestamosActivos >= 3) {
            return redirect()->route('usuario.historial')->with('error',
                'No puedes solicitar más de 3 préstamos activos.');
        }

        // Este middleware ya no se usará, podria eliminarlo pero lo nose.

        return $next($request);
    }
}
