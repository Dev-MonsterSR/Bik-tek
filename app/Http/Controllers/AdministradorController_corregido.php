<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrador;
use App\Models\Libro;
use App\Models\Usuario;
use App\Models\Categoria;
use App\Models\Trabajador;
use App\Models\Prestamo;
use App\Models\Devolucion;
use App\Models\Sancion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdministradorController extends Controller
{
    // Método para mostrar todos los administradores
    public function index()
    {
        $administradores = Administrador::all();
        return view('administradores.index', compact('administradores'));
    }

    // Método para mostrar el formulario de creación de un nuevo administrador
    public function create()
    {
        return view('administradores.create');
    }

    // Método para almacenar un nuevo administrador
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:50|unique:administrador,usuario',
            'clave' => 'required|string|max:255',
            'nombre' => 'nullable|string|max:100',
            'correo' => 'nullable|string|max:150',
        ]);

        $data = $request->all();
        $data['clave'] = Hash::make($request->clave); // Hashear la clave

        Administrador::create($data);
        return redirect()->route('administradores.index')->with('success', 'Administrador creado correctamente.');
    }

    // Método para mostrar un administrador específico
    public function show($id)
    {
        $administrador = Administrador::findOrFail($id);
        return view('administradores.show', compact('administrador'));
    }

    // Método para mostrar el formulario de edición de un administrador
    public function edit($id)
    {
        $administrador = Administrador::findOrFail($id);
        return view('administradores.edit', compact('administrador'));
    }

    // Método para actualizar un administrador específico
    public function update(Request $request, $id)
    {
        $request->validate([
            'usuario' => 'required|string|max:50|unique:administrador,usuario,'.$id.',id_admin',
            'clave' => 'required|string|max:255',
            'nombre' => 'nullable|string|max:100',
            'correo' => 'nullable|string|max:150',
        ]);

        $administrador = Administrador::findOrFail($id);
        $data = $request->all();
        $data['clave'] = Hash::make($request->clave); // Hashear la clave

        $administrador->update($data);
        return redirect()->route('administradores.index')->with('success', 'Administrador actualizado correctamente.');
    }

    // Método para eliminar un administrador
    public function destroy($id)
    {
        $administrador = Administrador::findOrFail($id);
        $administrador->delete();
        return redirect()->route('administradores.index')->with('success', 'Administrador eliminado correctamente.');
    }

    // Método para mostrar el panel de administración
    public function panel()
    {
        $libros = Libro::with('categoria')->get();
        $usuarios = Usuario::all();
        $trabajadores = Trabajador::all();
        $categorias = Categoria::all();

        return view('administrador', compact('libros', 'usuarios', 'trabajadores', 'categorias'));
    }

    public function grafico(Request $request)
    {
        $tipo = $request->tipo ?? 'prestamos';
        $rango = $request->rango ?? 'mes'; // 'dia', 'semana', 'mes', 'anio'

        // Verificar tipo especial de métricas
        if ($tipo === 'metricas') {
            $prestamosTotal = Prestamo::count();
            $usuariosTotales = Usuario::count();
            $usuariosActivos = Usuario::whereHas('prestamos', function($q) {
                $q->whereMonth('fecha_prestamo', now()->month);
            })->count();
            $sancionesActivas = Sancion::where('estado', 'activa')
                ->where('fecha_fin', '>=', now())
                ->count();
            $usuariosSancionados = Sancion::where('estado', 'activa')
                ->distinct('id_usuario')
                ->count();

            return response()->json([
                'prestamos' => $prestamosTotal,
                'usuarios_activos' => $usuariosActivos,
                'usuarios_totales' => $usuariosTotales,
                'devoluciones' => Devolucion::count(),
                'sanciones_activas' => $sancionesActivas,
                'usuarios_sancionados' => $usuariosSancionados,
                'tasa_cumplimiento' => '94%',
                'usuarios_frecuentes' => '12%',
                'libros_disponibles' => '85%',
                'prestamos_tiempo' => '92%',
                'efectividad_sistema' => '96%',
                'prestamos_vencidos' => Prestamo::where('estado', 'activo')
                    ->where('fecha_devolucion', '<', now())
                    ->count(),
                'sanciones_vencidas' => Sancion::where('estado', 'activa')
                    ->where('fecha_fin', '<', now())
                    ->count(),
                // Legacy support
                'pendientes' => Prestamo::where('estado', 'pendiente')->count(),
                'aceptados' => Prestamo::where('estado', 'activo')->count(),
                'denegados' => Prestamo::where('estado', 'denegado')->count(),
                'usuarios' => $usuariosTotales,
            ]);
        }

        // Métricas para el dashboard mejorado
        if ($tipo === 'estado_prestamos') {
            $pendientes = Prestamo::where('estado', 'pendiente')->count();
            $activos = Prestamo::where('estado', 'activo')->count();
            $devueltos = Prestamo::where('estado', 'entregado')->count();
            $denegados = Prestamo::where('estado', 'denegado')->count();

            return response()->json([
                'labels' => ['Activos', 'Completados', 'Pendientes', 'Denegados'],
                'data' => [$activos, $devueltos, $pendientes, $denegados]
            ]);
        }

        if ($tipo === 'top_libros') {
            $limit = $request->get('limit', 5);
            $libros = Prestamo::with('libro')
                ->selectRaw('id_libro, count(*) as prestamos')
                ->groupBy('id_libro')
                ->orderByDesc('prestamos')
                ->limit($limit)
                ->get()
                ->map(function($item) {
                    return [
                        'titulo' => $item->libro->titulo ?? 'Sin título',
                        'autor' => $item->libro->autor ?? 'Sin autor',
                        'prestamos' => $item->prestamos
                    ];
                });

            return response()->json(['data' => $libros]);
        }

        if ($tipo === 'metricas_sanciones') {
            $sancionesActivas = Sancion::where('estado', 'activa')
                ->where('fecha_fin', '>=', now())
                ->count();

            $sancionesCumplidasMes = Sancion::where('estado', 'cumplida')
                ->whereMonth('fecha_fin', now()->month)
                ->whereYear('fecha_fin', now()->year)
                ->count();

            $usuariosSancionados = Sancion::where('estado', 'activa')
                ->distinct('id_usuario')
                ->count();

            $promedioDias = Sancion::avg('dias_bloqueo') ?? 0;

            return response()->json([
                'activas' => $sancionesActivas,
                'cumplidas_mes' => $sancionesCumplidasMes,
                'usuarios_sancionados' => $usuariosSancionados,
                'promedio_dias' => round($promedioDias, 1)
            ]);
        }

        // Lista de sanciones para la tabla
        if ($request->has('action') && $request->action === 'listar_sanciones') {
            $query = Sancion::with('usuario');

            // Aplicar filtros
            if ($request->usuario) {
                $query->whereHas('usuario', function($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->usuario . '%')
                      ->orWhere('email', 'like', '%' . $request->usuario . '%');
                });
            }

            if ($request->estado) {
                $query->where('estado', $request->estado);
            }

            if ($request->tipo) {
                $query->where('tipo', $request->tipo);
            }

            $sanciones = $query->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function($sancion) {
                    return [
                        'id' => $sancion->id_sancion,
                        'usuario_nombre' => $sancion->usuario->nombre ?? 'Usuario eliminado',
                        'usuario_email' => $sancion->usuario->email ?? '',
                        'tipo' => $sancion->tipo,
                        'dias_bloqueo' => $sancion->dias_bloqueo,
                        'fecha_inicio' => $sancion->fecha_inicio,
                        'fecha_fin' => $sancion->fecha_fin,
                        'estado' => $sancion->estado,
                        'observaciones' => $sancion->observaciones
                    ];
                });

            return response()->json(['sanciones' => $sanciones]);
        }

        $inicio = $request->inicio ?? now()->subMonths(6)->startOfMonth()->toDateString();
        $fin = $request->fin ?? now()->endOfMonth()->toDateString();

        $labels = [];
        $data = [];

        // --- LIBROS MÁS SOLICITADOS ---
        if ($tipo === 'libros') {
            $libros = Prestamo::with('libro')
                ->selectRaw('id_libro, count(*) as prestamos')
                ->whereBetween('fecha_prestamo', [$inicio, $fin])
                ->groupBy('id_libro')
                ->orderByDesc('prestamos')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'titulo' => $item->libro->titulo ?? 'Sin título',
                        'autor' => $item->libro->autor ?? 'Sin autor',
                        'prestamos' => $item->prestamos
                    ];
                });

            $labels = $libros->pluck('titulo')->toArray();
            $data = $libros->pluck('prestamos')->toArray();

            return response()->json(['labels' => $labels, 'data' => $data]);
        }

        // --- OTROS GRÁFICOS DE ACTIVIDAD ---
        // Generar etiquetas basadas en el rango
        $period = collect();
        $groupFormat = '';

        if ($rango === 'dia') {
            $period = collect(Carbon::parse($inicio)->daysUntil($fin));
            $groupFormat = '%Y-%m-%d';
        } elseif ($rango === 'semana') {
            $period = collect(Carbon::parse($inicio)->weeksUntil($fin));
            $groupFormat = '%x-%v';
        } elseif ($rango === 'anio') {
            $period = collect(Carbon::parse($inicio)->yearsUntil($fin));
            $groupFormat = '%Y';
        } else {
            $period = collect(Carbon::parse($inicio)->monthsUntil($fin));
            $groupFormat = '%Y-%m';
        }

        // Generar etiquetas para el período
        foreach ($period as $date) {
            if ($rango === 'dia') {
                $labels[] = $date->format('d/m');
            } elseif ($rango === 'semana') {
                $labels[] = 'Sem ' . $date->weekOfYear();
            } elseif ($rango === 'anio') {
                $labels[] = $date->format('Y');
            } else {
                $labels[] = $date->format('M Y');
            }
        }

        // Obtener datos según el tipo
        if ($tipo === 'prestamos') {
            $resultados = Prestamo::selectRaw("DATE_FORMAT(fecha_prestamo, '{$groupFormat}') as periodo, COUNT(*) as total")
                ->whereBetween('fecha_prestamo', [$inicio, $fin])
                ->groupBy('periodo')
                ->orderBy('periodo')
                ->pluck('total', 'periodo')
                ->toArray();
        } elseif ($tipo === 'devoluciones') {
            $resultados = Devolucion::selectRaw("DATE_FORMAT(fecha_devolucion, '{$groupFormat}') as periodo, COUNT(*) as total")
                ->whereBetween('fecha_devolucion', [$inicio, $fin])
                ->groupBy('periodo')
                ->orderBy('periodo')
                ->pluck('total', 'periodo')
                ->toArray();
        } elseif ($tipo === 'usuarios') {
            $resultados = Usuario::selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as periodo, COUNT(*) as total")
                ->whereBetween('created_at', [$inicio, $fin])
                ->groupBy('periodo')
                ->orderBy('periodo')
                ->pluck('total', 'periodo')
                ->toArray();
        }

        // Rellenar datos faltantes con 0
        foreach ($period as $date) {
            $key = $date->format($rango === 'dia' ? 'Y-m-d' : ($rango === 'anio' ? 'Y' : 'Y-m'));
            $data[] = $resultados[$key] ?? 0;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    public function tabla(Request $request)
    {
        $tipo = $request->tipo ?? 'prestamos';
        $inicio = $request->inicio ?? now()->subMonth()->startOfMonth()->toDateString();
        $fin = $request->fin ?? now()->endOfMonth()->toDateString();

        $resultados = collect();

        switch ($tipo) {
            case 'prestamos':
                $resultados = Prestamo::with(['libro', 'usuario'])
                    ->whereBetween('fecha_prestamo', [$inicio, $fin])
                    ->orderBy('fecha_prestamo', 'desc')
                    ->get()
                    ->map(function($prestamo) {
                        return [
                            'libro' => $prestamo->libro->titulo ?? 'Libro no encontrado',
                            'usuario' => ($prestamo->usuario->nombre ?? '') . ' ' . ($prestamo->usuario->apellido ?? ''),
                            'fecha' => Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y'),
                            'estado' => ucfirst($prestamo->estado)
                        ];
                    });
                break;

            case 'devoluciones':
                $resultados = Devolucion::with(['prestamo.libro', 'prestamo.usuario'])
                    ->whereBetween('fecha_devolucion', [$inicio, $fin])
                    ->orderBy('fecha_devolucion', 'desc')
                    ->get()
                    ->map(function($devolucion) {
                        return [
                            'libro' => $devolucion->prestamo->libro->titulo ?? 'Libro no encontrado',
                            'usuario' => ($devolucion->prestamo->usuario->nombre ?? '') . ' ' . ($devolucion->prestamo->usuario->apellido ?? ''),
                            'fecha' => Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y'),
                            'estado' => ucfirst($devolucion->estado_libro)
                        ];
                    });
                break;

            case 'usuarios':
                $resultados = Usuario::whereBetween('created_at', [$inicio, $fin])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function($usuario) {
                        return [
                            'nombre' => $usuario->nombre,
                            'apellido' => $usuario->apellido,
                            'email' => $usuario->email,
                            'fecha' => Carbon::parse($usuario->created_at)->format('d/m/Y')
                        ];
                    });
                break;

            case 'sanciones':
                $resultados = Sancion::with('usuario')
                    ->whereBetween('fecha_inicio', [$inicio, $fin])
                    ->orderBy('fecha_inicio', 'desc')
                    ->get()
                    ->map(function($sancion) {
                        return [
                            'usuario' => ($sancion->usuario->nombre ?? '') . ' ' . ($sancion->usuario->apellido ?? ''),
                            'dias' => $sancion->dias_bloqueo,
                            'inicio' => Carbon::parse($sancion->fecha_inicio)->format('d/m/Y'),
                            'fin' => Carbon::parse($sancion->fecha_fin)->format('d/m/Y')
                        ];
                    });
                break;

            case 'libros':
                $resultados = Prestamo::with('libro')
                    ->selectRaw('id_libro, count(*) as total')
                    ->whereBetween('fecha_prestamo', [$inicio, $fin])
                    ->groupBy('id_libro')
                    ->orderByDesc('total')
                    ->limit(20)
                    ->get()
                    ->map(function($item) {
                        return [
                            'libro' => $item->libro->titulo ?? 'Sin título',
                            'autor' => $item->libro->autor ?? 'Sin autor',
                            'total' => $item->total
                        ];
                    });
                break;
        }

        // Si se solicita exportación
        if ($request->export) {
            return $this->exportToCSV($resultados, $tipo);
        }

        return response()->json(['resultados' => $resultados]);
    }

    private function exportToCSV($data, $tipo)
    {
        $filename = "reporte_{$tipo}_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data, $tipo) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers según tipo
            if ($tipo === 'prestamos') {
                fputcsv($file, ['#', 'Libro', 'Usuario', 'Fecha', 'Estado']);
            } elseif ($tipo === 'devoluciones') {
                fputcsv($file, ['#', 'Libro', 'Usuario', 'Fecha', 'Estado']);
            } elseif ($tipo === 'usuarios') {
                fputcsv($file, ['#', 'Nombre', 'Apellido', 'Email', 'Fecha']);
            } elseif ($tipo === 'sanciones') {
                fputcsv($file, ['#', 'Usuario', 'Días', 'Inicio', 'Fin']);
            } elseif ($tipo === 'libros') {
                fputcsv($file, ['#', 'Libro', 'Autor', 'Préstamos']);
            }

            // Datos
            foreach ($data as $index => $row) {
                $csvRow = [$index + 1];
                foreach ($row as $value) {
                    $csvRow[] = $value;
                }
                fputcsv($file, $csvRow);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function gestionarSancion(Request $request, $accion, $id = null)
    {
        try {
            switch ($accion) {
                case 'crear':
                    $request->validate([
                        'id_usuario' => 'required|exists:usuarios,id_usuario',
                        'tipo' => 'required|string',
                        'dias_bloqueo' => 'required|integer|min:1',
                        'observaciones' => 'required|string'
                    ]);

                    $sancion = new Sancion();
                    $sancion->id_usuario = $request->id_usuario;
                    $sancion->tipo = $request->tipo;
                    $sancion->dias_bloqueo = $request->dias_bloqueo;
                    $sancion->fecha_inicio = now();
                    $sancion->fecha_fin = now()->addDays($request->dias_bloqueo);
                    $sancion->estado = 'activa';
                    $sancion->observaciones = $request->observaciones;
                    $sancion->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Sanción aplicada correctamente'
                    ]);

                case 'levantar':
                    $sancion = Sancion::findOrFail($id);
                    $sancion->estado = 'levantada';
                    $sancion->fecha_fin = now();
                    $sancion->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Sanción levantada correctamente'
                    ]);

                case 'eliminar':
                    $sancion = Sancion::findOrFail($id);
                    $sancion->delete();

                    return response()->json([
                        'success' => true,
                        'message' => 'Sanción eliminada correctamente'
                    ]);

                case 'levantar_vencidas':
                    $sancionesVencidas = Sancion::where('estado', 'activa')
                        ->where('fecha_fin', '<', now())
                        ->update(['estado' => 'cumplida']);

                    return response()->json([
                        'success' => true,
                        'message' => "Se levantaron {$sancionesVencidas} sanciones vencidas"
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Acción no reconocida'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
