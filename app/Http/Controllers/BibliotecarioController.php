<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajador;
use App\Models\Prestamo;
use App\Models\Devolucion;
use App\Models\Sancion;
use App\Models\Libro;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BibliotecarioController extends Controller
{
    public function __construct()
    {
        // Aplicar middleware a través de las rutas en lugar del constructor
    }

    // Mostrar dashboard
    public function dashboard(Request $request)
    {
        // Aplicar sanciones automáticas por retrasos
        $this->aplicarSancionesAutomaticas();

        // Redirigir a la vista de trabajadores
        return view('trabajadores.dashboard', [
            'prestamos' => Prestamo::with(['usuario', 'libro'])
                ->where('estado', 'pendiente')
                ->get(),
            'prestamosEnCurso' => Prestamo::with(['usuario', 'libro'])
                ->where('estado', 'activo')
                ->whereDoesntHave('devoluciones')
                ->get(),
            'usuarios' => Usuario::all(),
            'libros' => Libro::orderBy('codigo')->get(),
            'devoluciones' => Devolucion::with(['prestamo.usuario', 'prestamo.libro'])
                ->latest()
                ->get()
        ]);
    }

    // Confirmar préstamo
    public function confirmarPrestamo(Request $request, $id)
    {
        $prestamo = Prestamo::findOrFail($id);
        $libro = Libro::findOrFail($prestamo->id_libro);

        // Verificar disponibilidad
        if ($libro->disponibles <= 0) {
            return back()->with('error', 'El libro ya no está disponible.');
        }

        // Verificar sanciones del usuario
        $sancionActiva = Sancion::where('id_usuario', $prestamo->id_usuario)
            ->where('fecha_fin', '>', now())
            ->first();

        if ($sancionActiva) {
            return back()->with('error', 'El usuario tiene una sanción activa hasta ' .
                Carbon::parse($sancionActiva->fecha_fin)->format('d/m/Y'));
        }

        // Verificar límite de préstamos
        $prestamosActivos = Prestamo::where('id_usuario', $prestamo->id_usuario)
            ->where('estado', 'activo')
            ->count();

        if ($prestamosActivos >= 3) {
            return back()->with('error', 'El usuario ha alcanzado el límite de préstamos activos.');
        }

        // Actualizar estado y disponibilidad
        $prestamo->estado = 'activo';
        $prestamo->save();

        $libro->disponibles = max(0, $libro->disponibles - 1);
        $libro->save();

        return redirect()
            ->route('bibliotecario.dashboard')
            ->with('success', 'Préstamo confirmado correctamente.');
    }

    // Registrar devolución
    public function registrarDevolucion(Request $request)
    {
        $request->validate([
            'id_prestamo' => 'required|exists:prestamo,id_prestamo'
        ]);

        $prestamo = Prestamo::findOrFail($request->id_prestamo);

        if ($prestamo->estado !== 'activo') {
            return back()->with('error', 'Este préstamo no está activo.');
        }

        // Determinar estado del libro basado en fecha de devolución
        $fechaDevolucion = \Carbon\Carbon::parse($prestamo->fecha_devolucion);
        $fechaActual = now();
        $estadoLibro = $fechaActual->lte($fechaDevolucion) ? 'A tiempo' : 'Tarde';

        // Registrar devolución
        $devolucion = Devolucion::create([
            'id_prestamo' => $prestamo->id_prestamo,
            'fecha_devolucion' => $fechaActual,
            'estado_libro' => $estadoLibro,
            'observaciones' => 'Devolución registrada por bibliotecario'
        ]);

        // Actualizar estado del préstamo
        $prestamo->estado = 'devuelto';
        $prestamo->fecha_entrega_real = $fechaActual;
        $prestamo->save();

        // Actualizar disponibilidad del libro
        $libro = Libro::find($prestamo->id_libro);
        if ($libro) {
            $libro->disponibles += 1;
            $libro->save();
        }

        return redirect()
            ->route('bibliotecario.dashboard', ['tab' => $request->tab ?? 'devoluciones'])
            ->with('success', 'Devolución registrada correctamente.');
    }

    // Aplicar sanción
    public function aplicarSancion(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'dias_bloqueo' => 'required|integer|min:1',
            'tipo' => 'required|in:retraso,daño,perdida,otro',
            'observaciones' => 'nullable|string|max:255'
        ]);

        // Verificar si ya tiene una sanción activa
        $sancionActiva = Sancion::where('id_usuario', $request->id_usuario)
            ->where('fecha_fin', '>', now())
            ->first();

        if ($sancionActiva) {
            return back()->with('error', 'El usuario ya tiene una sanción activa hasta ' .
                Carbon::parse($sancionActiva->fecha_fin)->format('d/m/Y'));
        }

        Sancion::create([
            'id_usuario' => $request->id_usuario,
            'dias_bloqueo' => (int) $request->dias_bloqueo,
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addDays((int) $request->dias_bloqueo),
            'tipo' => $request->tipo,
            'observaciones' => $request->observaciones
        ]);

        return redirect()
            ->route('bibliotecario.dashboard')
            ->with('success', 'Sanción aplicada correctamente.');
    }

    // Modificar disponibilidad de libro
    public function modificarDisponibilidad(Request $request, $codigo)
    {
        $libro = Libro::where('codigo', $codigo)->firstOrFail();
        $libro->disponibles = $request->disponibilidad;
        $libro->save();
        return redirect()
            ->route('bibliotecario.dashboard', ['tab' => $request->tab ?? 'libros'])
            ->with('success', 'Disponibilidad modificada');
    }

    // Completar/levantar sanción
    public function completarSancion(Request $request)
    {
        $request->validate([
            'id_sancion' => 'required|exists:sanciones,id_sancion'
        ]);

        $sancion = Sancion::findOrFail($request->id_sancion);
        $sancion->estado = 'completada';
        $sancion->fecha_fin = now();
        $sancion->save();

        // Si es una petición AJAX, devolver JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sanción levantada correctamente.'
            ]);
        }

        return redirect()
            ->route('bibliotecario.dashboard', ['tab' => 'sanciones'])
            ->with('success', 'Sanción levantada correctamente.');
    }

    // Denegar préstamo
    public function denegarPrestamo(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:255'
        ]);

        $prestamo = Prestamo::findOrFail($id);

        if ($prestamo->estado !== 'pendiente') {
            return back()->with('error', 'Este préstamo ya fue procesado.');
        }

        $prestamo->estado = 'denegado';
        $prestamo->observaciones = $request->observaciones;
        $prestamo->save();

        return redirect()
            ->route('bibliotecario.dashboard')
            ->with('success', 'Préstamo denegado correctamente.');
    }

    // Eliminar sanción del historial
    public function eliminarSancion(Request $request)
    {
        $request->validate([
            'id_sancion' => 'required|exists:sanciones,id_sancion'
        ]);

        $sancion = Sancion::findOrFail($request->id_sancion);

        // Solo permitir eliminar sanciones completadas (no activas)
        if ($sancion->estado === 'activa' && $sancion->fecha_fin > now()) {
            return response()->json(['success' => false, 'message' => 'No se pueden eliminar sanciones activas']);
        }

        $sancion->delete();

        return response()->json(['success' => true, 'message' => 'Sanción eliminada del historial']);
    }

    // Obtener sanciones de un usuario
    public function getSancionesUsuario($id)
    {
        $sanciones = Sancion::where('id_usuario', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sanciones);
    }

    // Obtener préstamos de un usuario
    public function getPrestamosUsuario($id)
    {
        $prestamos = Prestamo::with(['libro'])
            ->where('id_usuario', $id)
            ->orderBy('fecha_prestamo', 'desc')
            ->get();

        return response()->json($prestamos);
    }

    // Aplicar sanciones automáticas por retrasos
    private function aplicarSancionesAutomaticas()
    {
        // Obtener préstamos activos que ya pasaron su fecha de devolución
        $prestamosAtrasados = Prestamo::where('estado', 'activo')
            ->where('fecha_devolucion', '<', now()->toDateString())
            ->whereDoesntHave('devoluciones')
            ->get();

        foreach ($prestamosAtrasados as $prestamo) {
            // Verificar si el usuario ya tiene una sanción activa
            $sancionActiva = Sancion::where('id_usuario', $prestamo->id_usuario)
                ->where('estado', 'activa')
                ->where('fecha_fin', '>', now())
                ->first();

            // Solo aplicar sanción si no tiene una activa
            if (!$sancionActiva) {
                $diasAtraso = now()->diffInDays($prestamo->fecha_devolucion, false);

                if ($diasAtraso > 0) {
                    Sancion::create([
                        'id_usuario' => $prestamo->id_usuario,
                        'dias_bloqueo' => $diasAtraso * 5, // 5 días por cada día de atraso
                        'fecha_inicio' => now(),
                        'fecha_fin' => now()->addDays($diasAtraso * 5),
                        'tipo' => 'retraso',
                        'estado' => 'activa',
                        'observaciones' => "Sanción automática por {$diasAtraso} día(s) de retraso en el libro: {$prestamo->libro->titulo}"
                    ]);
                }
            }
        }
    }
}



