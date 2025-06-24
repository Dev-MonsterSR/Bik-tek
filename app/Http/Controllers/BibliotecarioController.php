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

        $buscar = $request->get('buscar');

        // Obtener usuarios con solicitudes activas de préstamos y devoluciones
        $usuariosConSolicitudes = collect();

        // Usuarios con préstamos pendientes
        $usuariosConPrestamos = Usuario::whereHas('prestamos', function($query) {
            $query->where('estado', 'pendiente');
        })->with(['prestamos' => function($query) {
            $query->where('estado', 'pendiente')->with('libro');
        }])->get();

        // Usuarios con préstamos activos (para devolución)
        $usuariosConDevoluciones = Usuario::whereHas('prestamos', function($query) {
            $query->where('estado', 'activo')->whereDoesntHave('devoluciones');
        })->with(['prestamos' => function($query) {
            $query->where('estado', 'activo')->whereDoesntHave('devoluciones')->with('libro');
        }])->get();

        // Combinar y organizar usuarios únicos
        $todosLosUsuarios = $usuariosConPrestamos->merge($usuariosConDevoluciones)->unique('id_usuario');

        // Filtrar por búsqueda si existe
        if ($buscar) {
            $todosLosUsuarios = $todosLosUsuarios->filter(function($usuario) use ($buscar) {
                return stripos($usuario->email, $buscar) !== false ||
                       stripos($usuario->nombre, $buscar) !== false ||
                       stripos($usuario->apellido, $buscar) !== false ||
                       stripos($usuario->nombre . ' ' . $usuario->apellido, $buscar) !== false;
            });
        }

        // Organizar datos para cada usuario
        $usuariosConSolicitudes = $todosLosUsuarios->map(function($usuario) {
            $prestamosPendientes = $usuario->prestamos->where('estado', 'pendiente');
            $prestamosActivos = $usuario->prestamos->where('estado', 'activo');

            return [
                'usuario' => $usuario,
                'prestamos_pendientes' => $prestamosPendientes,
                'prestamos_activos' => $prestamosActivos,
                'total_pendientes' => $prestamosPendientes->count(),
                'total_activos' => $prestamosActivos->count(),
                'tiene_solicitudes' => $prestamosPendientes->count() > 0 || $prestamosActivos->count() > 0
            ];
        })->filter(function($userData) {
            return $userData['tiene_solicitudes'];
        });

        // Obtener todos los préstamos necesarios para el dashboard
        $todosLosPrestamos = Prestamo::with(['usuario', 'libro'])
            ->whereIn('estado', ['pendiente', 'activo', 'denegado', 'entregado'])
            ->get();

        return view('trabajadores.dashboard', [
            'usuariosConSolicitudes' => $usuariosConSolicitudes,
            'prestamos' => $todosLosPrestamos, // Todos los préstamos para que la vista pueda filtrar por estado
            'usuarios' => Usuario::all(),
            'libros' => Libro::orderBy('codigo')->get(),
            'devoluciones' => Devolucion::with(['prestamo.usuario', 'prestamo.libro'])
                ->latest()
                ->get(),
            'buscar' => $buscar
        ]);
    }

    // Buscar usuarios con solicitudes activas (AJAX)
    public function buscarUsuarios(Request $request)
    {
        $buscar = $request->get('q');

        // Obtener usuarios con solicitudes activas
        $usuariosConPrestamos = Usuario::whereHas('prestamos', function($query) {
            $query->where('estado', 'pendiente');
        })->with(['prestamos' => function($query) {
            $query->where('estado', 'pendiente')->with('libro');
        }])->get();

        $usuariosConDevoluciones = Usuario::whereHas('prestamos', function($query) {
            $query->where('estado', 'activo')->whereDoesntHave('devoluciones');
        })->with(['prestamos' => function($query) {
            $query->where('estado', 'activo')->whereDoesntHave('devoluciones')->with('libro');
        }])->get();

        $todosLosUsuarios = $usuariosConPrestamos->merge($usuariosConDevoluciones)->unique('id_usuario');

        // Filtrar por búsqueda
        if ($buscar) {
            $todosLosUsuarios = $todosLosUsuarios->filter(function($usuario) use ($buscar) {
                return stripos($usuario->email, $buscar) !== false ||
                       stripos($usuario->nombre, $buscar) !== false ||
                       stripos($usuario->apellido, $buscar) !== false ||
                       stripos($usuario->nombre . ' ' . $usuario->apellido, $buscar) !== false;
            });
        }

        // Organizar datos
        $usuariosConSolicitudes = $todosLosUsuarios->map(function($usuario) {
            $prestamosPendientes = $usuario->prestamos->where('estado', 'pendiente');
            $prestamosActivos = $usuario->prestamos->where('estado', 'activo');

            return [
                'usuario' => $usuario,
                'prestamos_pendientes' => $prestamosPendientes,
                'prestamos_activos' => $prestamosActivos,
                'total_pendientes' => $prestamosPendientes->count(),
                'total_activos' => $prestamosActivos->count(),
                'tiene_solicitudes' => $prestamosPendientes->count() > 0 || $prestamosActivos->count() > 0
            ];
        })->filter(function($userData) {
            return $userData['tiene_solicitudes'];
        });

        return response()->json([
            'success' => true,
            'usuarios' => $usuariosConSolicitudes->values()->toArray()
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

        // Determinar el tab de redirección
        $tab = $request->input('tab', 'prestamos');

        return redirect()
            ->route('bibliotecario.dashboard', ['tab' => $tab])
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

    // ========== MÉTODOS AJAX ==========

    // Confirmar préstamo vía AJAX
    public function confirmarPrestamoAjax(Request $request, $id)
    {
        try {
            $prestamo = Prestamo::findOrFail($id);
            $libro = Libro::findOrFail($prestamo->id_libro);

            // Verificar disponibilidad
            if ($libro->disponibles <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'El libro ya no está disponible.'
                ]);
            }

            // Verificar sanciones del usuario
            $sancionActiva = Sancion::where('id_usuario', $prestamo->id_usuario)
                ->where('fecha_fin', '>', now())
                ->first();

            if ($sancionActiva) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario tiene una sanción activa hasta ' .
                        Carbon::parse($sancionActiva->fecha_fin)->format('d/m/Y')
                ]);
            }

            // Verificar límite de préstamos
            $prestamosActivos = Prestamo::where('id_usuario', $prestamo->id_usuario)
                ->where('estado', 'activo')
                ->count();

            if ($prestamosActivos >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario ha alcanzado el límite de préstamos activos.'
                ]);
            }

            // Actualizar estado y disponibilidad
            $prestamo->estado = 'activo';
            $prestamo->save();

            $libro->disponibles = max(0, $libro->disponibles - 1);
            $libro->save();

            return response()->json([
                'success' => true,
                'message' => 'Préstamo confirmado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }

    // Denegar préstamo vía AJAX
    public function denegarPrestamoAjax(Request $request, $id)
    {
        try {
            $request->validate([
                'observaciones' => 'required|string|max:255'
            ]);

            $prestamo = Prestamo::findOrFail($id);
            $prestamo->estado = 'denegado';
            $prestamo->observaciones = $request->observaciones;
            $prestamo->save();

            return response()->json([
                'success' => true,
                'message' => 'Préstamo denegado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }

    // Registrar devolución vía AJAX
    public function registrarDevolucionAjax(Request $request)
    {
        try {
            $request->validate([
                'id_prestamo' => 'required|exists:prestamo,id_prestamo'
            ]);

            $prestamo = Prestamo::findOrFail($request->id_prestamo);

            if ($prestamo->estado !== 'activo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este préstamo no está activo.'
                ]);
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
                'observaciones' => 'Devolución registrada por bibliotecario vía AJAX'
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

            return response()->json([
                'success' => true,
                'message' => 'Devolución registrada correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener estadísticas actualizadas para AJAX
    public function obtenerEstadisticasAjax(Request $request)
    {
        try {
            $tab = $request->input('tab', 'prestamos');

            if ($tab === 'prestamos') {
                // Estadísticas de préstamos
                $prestamos = Prestamo::all();
                $pendientes = $prestamos->where('estado', 'pendiente')->count();
                $activos = $prestamos->where('estado', 'activo')->count();
                $denegados = $prestamos->where('estado', 'denegado')->count();
                $entregados = $prestamos->where('estado', 'entregado')->count();

                // Obtener usuarios con préstamos pendientes agrupados
                $usuariosConPrestamos = Prestamo::where('estado', 'pendiente')
                    ->with(['usuario', 'libro'])
                    ->get()
                    ->groupBy('id_usuario')
                    ->map(function($prestamos, $idUsuario) {
                        $usuario = $prestamos->first()->usuario;
                        return [
                            'id_usuario' => $idUsuario,
                            'nombre_completo' => ($usuario->nombre ?? '') . ' ' . ($usuario->apellido ?? ''),
                            'email' => $usuario->email ?? 'N/A',
                            'count' => $prestamos->count()
                        ];
                    })
                    ->values();

                return response()->json([
                    'success' => true,
                    'estadisticas' => [
                        'pendientes' => $pendientes,
                        'activos' => $activos,
                        'denegados' => $denegados,
                        'entregados' => $entregados
                    ],
                    'usuarios' => $usuariosConPrestamos
                ]);

            } elseif ($tab === 'devoluciones') {
                // Estadísticas de devoluciones
                $prestamosActivos = Prestamo::where('estado', 'activo')
                    ->whereDoesntHave('devoluciones')
                    ->get();

                $now = \Carbon\Carbon::now();
                $enPlazo = $prestamosActivos->filter(function($p) use ($now) {
                    return $now->lte(\Carbon\Carbon::parse($p->fecha_devolucion));
                })->count();

                $atrasados = $prestamosActivos->filter(function($p) use ($now) {
                    return $now->gt(\Carbon\Carbon::parse($p->fecha_devolucion));
                })->count();

                // Obtener usuarios con devoluciones pendientes agrupados
                $usuariosConDevoluciones = $prestamosActivos
                    ->groupBy('id_usuario')
                    ->map(function($prestamos, $idUsuario) use ($now) {
                        $usuario = $prestamos->first()->usuario;
                        $atrasados = $prestamos->filter(function($p) use ($now) {
                            return $now->gt(\Carbon\Carbon::parse($p->fecha_devolucion));
                        })->count();
                        $enPlazo = $prestamos->filter(function($p) use ($now) {
                            return $now->lte(\Carbon\Carbon::parse($p->fecha_devolucion));
                        })->count();

                        return [
                            'id_usuario' => $idUsuario,
                            'nombre_completo' => ($usuario->nombre ?? '') . ' ' . ($usuario->apellido ?? ''),
                            'email' => $usuario->email ?? 'N/A',
                            'atrasados' => $atrasados,
                            'en_plazo' => $enPlazo,
                            'total' => $prestamos->count()
                        ];
                    })
                    ->values();

                return response()->json([
                    'success' => true,
                    'estadisticas' => [
                        'en_plazo' => $enPlazo,
                        'atrasados' => $atrasados,
                        'total' => $prestamosActivos->count()
                    ],
                    'usuarios' => $usuariosConDevoluciones
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Tab no válido']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }
}



