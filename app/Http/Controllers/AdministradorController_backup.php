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
            $prestamosTotal = \App\Models\Prestamo::count();
            $usuariosTotales = \App\Models\Usuario::count();
            $usuariosActivos = \App\Models\Usuario::whereHas('prestamos', function($q) {
                $q->whereMonth('fecha_prestamo', now()->month);
            })->count();
            $sancionesActivas = \App\Models\Sancion::where('estado', 'activa')
                ->where('fecha_fin', '>=', now())
                ->count();
            $usuariosSancionados = \App\Models\Sancion::where('estado', 'activa')
                ->distinct('id_usuario')
                ->count();

            return response()->json([
                'prestamos' => $prestamosTotal,
                'usuarios_activos' => $usuariosActivos,
                'usuarios_totales' => $usuariosTotales,
                'devoluciones' => \App\Models\Devolucion::count(),
                'sanciones_activas' => $sancionesActivas,
                'usuarios_sancionados' => $usuariosSancionados,
                'tasa_cumplimiento' => '94%',
                'usuarios_frecuentes' => '12%',
                'libros_disponibles' => '85%',
                'prestamos_tiempo' => '92%',
                'efectividad_sistema' => '96%',
                'prestamos_vencidos' => \App\Models\Prestamo::where('estado', 'activo')
                    ->where('fecha_devolucion', '<', now())
                    ->count(),
                'sanciones_vencidas' => \App\Models\Sancion::where('estado', 'activa')
                    ->where('fecha_fin', '<', now())
                    ->count(),
                // Legacy support
                'pendientes' => \App\Models\Prestamo::where('estado', 'pendiente')->count(),
                'aceptados' => \App\Models\Prestamo::where('estado', 'activo')->count(),
                'denegados' => \App\Models\Prestamo::where('estado', 'denegado')->count(),
                'usuarios' => $usuariosTotales,
            ]);
        }

        // Métricas para el dashboard mejorado
        if ($tipo === 'estado_prestamos') {
            $pendientes = \App\Models\Prestamo::where('estado', 'pendiente')->count();
            $activos = \App\Models\Prestamo::where('estado', 'activo')->count();
            $devueltos = \App\Models\Prestamo::where('estado', 'entregado')->count();
            $denegados = \App\Models\Prestamo::where('estado', 'denegado')->count();

            return response()->json([
                'labels' => ['Activos', 'Completados', 'Pendientes', 'Denegados'],
                'data' => [$activos, $devueltos, $pendientes, $denegados]
            ]);
        }

        if ($tipo === 'top_libros') {
            $limit = $request->get('limit', 5);
            $libros = \App\Models\Prestamo::with('libro')
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
            $sancionesActivas = \App\Models\Sancion::where('estado', 'activa')
                ->where('fecha_fin', '>=', now())
                ->count();

            $sancionesCumplidasMes = \App\Models\Sancion::where('estado', 'cumplida')
                ->whereMonth('fecha_fin', now()->month)
                ->whereYear('fecha_fin', now()->year)
                ->count();

            $usuariosSancionados = \App\Models\Sancion::where('estado', 'activa')
                ->distinct('id_usuario')
                ->count();

            $promedioDias = \App\Models\Sancion::avg('dias_bloqueo') ?? 0;

            return response()->json([
                'activas' => $sancionesActivas,
                'cumplidas_mes' => $sancionesCumplidasMes,
                'usuarios_sancionados' => $usuariosSancionados,
                'promedio_dias' => round($promedioDias, 1)
            ]);
        }

        // Lista de sanciones para la tabla
        if ($request->has('action') && $request->action === 'listar_sanciones') {
            $query = \App\Models\Sancion::with('usuario');

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
            // Agrupa por rango
            $query = \App\Models\Prestamo::with('libro')
                ->whereBetween('fecha_prestamo', [$inicio, $fin]);

            // Agrupación por rango
            switch ($rango) {
                case 'dia':
                    $groupFormat = '%Y-%m-%d';
                    $labelFormat = 'd M Y';
                    break;
                case 'semana':
                    $groupFormat = '%x-%v'; // Año-Semana ISO
                    $labelFormat = '"Semana "W, Y';
                    break;
                case 'anio':
                    $groupFormat = '%Y';
                    $labelFormat = 'Y';
                    break;
                default:
                    $groupFormat = '%Y-%m';
                    $labelFormat = 'M Y';
            }

            // Agrupa por libro y rango
            $prestamos = $query
                ->selectRaw("id_libro, COUNT(*) as total, DATE_FORMAT(fecha_prestamo, '{$groupFormat}') as periodo")
                ->groupBy('periodo', 'id_libro')
                ->orderBy('periodo')
                ->orderByDesc('total')
                ->get();

            // Agrupa por periodo
            $agrupados = $prestamos->groupBy('periodo');

            // Solo el último periodo (más reciente)
            $ultimoPeriodo = $agrupados->keys()->last();
            $topLibros = $agrupados[$ultimoPeriodo] ?? collect();

            // Top 4 + Otros
            $top4 = $topLibros->take(4);
            $otros = $topLibros->slice(4);

            $labels = $top4->map(fn($p) => $p->libro->titulo ?? 'Desconocido')->toArray();
            $data = $top4->map(fn($p) => $p->total)->toArray();

            if ($otros->count() > 0) {
                $labels[] = 'Otros...';
                $data[] = $otros->sum('total');
            }

            return response()->json(['labels' => $labels, 'data' => $data]);
        }

        // --- OTROS GRÁFICOS ---
        // --- OTROS GRÁFICOS DE ACTIVIDAD ---
        // Agrupa por rango real
        if ($rango === 'dia') {
            $period = \Carbon\CarbonPeriod::create($inicio, '1 day', $fin);
            $groupFormat = '%Y-%m-%d';
            $labelFormat = 'd/m';
        } elseif ($rango === 'semana') {
            $period = \Carbon\CarbonPeriod::create($inicio, '1 week', $fin);
            $groupFormat = '%x-%v';
            $labelFormat = 'W/Y';
        } elseif ($rango === 'anio') {
            $period = \Carbon\CarbonPeriod::create($inicio, '1 year', $fin);
            $groupFormat = '%Y';
            $labelFormat = 'Y';
        } else {
            $period = \Carbon\CarbonPeriod::create($inicio, '1 month', $fin);
            $groupFormat = '%Y-%m';
            $labelFormat = 'm/Y';
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
            $resultados = \App\Models\Prestamo::selectRaw("DATE_FORMAT(fecha_prestamo, '{$groupFormat}') as periodo, COUNT(*) as total")
                ->whereBetween('fecha_prestamo', [$inicio, $fin])
                ->groupBy('periodo')
                ->orderBy('periodo')
                ->pluck('total', 'periodo')
                ->toArray();
        } elseif ($tipo === 'devoluciones') {
            $resultados = \App\Models\Devolucion::selectRaw("DATE_FORMAT(fecha_devolucion, '{$groupFormat}') as periodo, COUNT(*) as total")
                ->whereBetween('fecha_devolucion', [$inicio, $fin])
                ->groupBy('periodo')
                ->orderBy('periodo')
                ->pluck('total', 'periodo')
                ->toArray();
        } elseif ($tipo === 'usuarios') {
            $resultados = \App\Models\Usuario::selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as periodo, COUNT(*) as total")
                ->whereBetween('created_at', [$inicio, $fin])
                ->groupBy('periodo')
                ->orderBy('periodo')
                ->pluck('total', 'periodo')
                ->toArray();
        } else {
            $resultados = [];
        }

        // Mapear datos a las etiquetas
        $data = [];
        foreach ($period as $date) {
            if ($rango === 'dia') {
                $key = $date->format('Y-m-d');
            } elseif ($rango === 'semana') {
                $key = $date->format('o-W'); // Año ISO - Semana ISO
            } elseif ($rango === 'anio') {
                $key = $date->format('Y');
            } else {
                $key = $date->format('Y-m');
            }

            $data[] = $resultados[$key] ?? 0;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
            $labelFormat = 'd M Y';
        } elseif ($rango === 'semana') {
            $period = \Carbon\CarbonPeriod::create($inicio, '1 week', $fin);
            $labelFormat = '"Semana "W, Y';
        } elseif ($rango === 'anio') {
            $period = \Carbon\CarbonPeriod::create($inicio, '1 year', $fin);
            $labelFormat = 'Y';
        } else { // mes
            $period = \Carbon\CarbonPeriod::create($inicio, '1 month', $fin);
            $labelFormat = 'M Y';
        }

        foreach ($period as $date) {
            $from = $date->copy();
            if ($rango === 'dia') {
                $to = $from->copy();
            } elseif ($rango === 'semana') {
                $to = $from->copy()->addDays(6);
            } elseif ($rango === 'anio') {
                $to = $from->copy()->endOfYear();
            } else { // mes
                $to = $from->copy()->endOfMonth();
            }
            $labels[] = $from->format($labelFormat);

            switch ($tipo) {
                case 'prestamos':
                    $data[] = \App\Models\Prestamo::whereBetween('fecha_prestamo', [$from->format('Y-m-d'), $to->format('Y-m-d')])->count();
                    break;
                case 'devoluciones':
                    $data[] = \App\Models\Devolucion::whereBetween('fecha_devolucion', [$from->format('Y-m-d'), $to->format('Y-m-d')])->count();
                    break;
                case 'usuarios':
                    $data[] = \App\Models\Usuario::whereBetween('fecha_registro', [$from->format('Y-m-d'), $to->format('Y-m-d')])->count();
                    break;
                case 'sanciones':
                    $data[] = \App\Models\Sancion::whereBetween('fecha_inicio', [$from->format('Y-m-d'), $to->format('Y-m-d')])->count();
                    break;
            }
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    public function tabla(Request $request)
    {
        $tipo = $request->tipo ?? 'prestamos';
        $inicio = $request->inicio ?? now()->subMonths(6)->startOfMonth()->toDateString();
        $fin = $request->fin ?? now()->endOfMonth()->toDateString();
        $export = $request->has('export') || $request->formato === 'excel';

        $resultados = [];
        switch ($tipo) {
            case 'prestamos':
                $query = \App\Models\Prestamo::with('usuario', 'libro')
                    ->whereBetween('fecha_prestamo', [$inicio, $fin])
                    ->orderBy('fecha_prestamo', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($p) {
                    return [
                        'id' => $p->id_prestamo,
                        'libro' => $p->libro->titulo ?? '-',
                        'usuario' => $p->usuario->nombre ?? '-',
                        'fecha' => $p->fecha_prestamo,
                        'estado' => ucfirst($p->estado)
                    ];
                });
                break;
            case 'devoluciones':
                $query = \App\Models\Devolucion::with('prestamo.usuario', 'prestamo.libro')
                    ->whereBetween('fecha_devolucion', [$inicio, $fin])
                    ->orderBy('fecha_devolucion', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($d) {
                    return [
                        'id' => $d->id_devolucion,
                        'libro' => $d->prestamo->libro->titulo ?? '-',
                        'usuario' => $d->prestamo->usuario->nombre ?? '-',
                        'fecha' => $d->fecha_devolucion,
                        'estado' => $d->estado_libro
                    ];
                });
                break;
            case 'usuarios':
                $query = \App\Models\Usuario::whereBetween('fecha_registro', [$inicio, $fin])
                    ->orderBy('fecha_registro', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($u) {
                    return [
                        'id' => $u->id_usuario,
                        'nombre' => $u->nombre,
                        'apellido' => $u->apellido,
                        'email' => $u->email,
                        'fecha' => $u->fecha_registro
                    ];
                });
                break;
            case 'sanciones':
                $query = \App\Models\Sancion::with('usuario')
                    ->whereBetween('fecha_inicio', [$inicio, $fin])
                    ->orderBy('fecha_inicio', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($s) {
                    return [
                        'id' => $s->id_sancion,
                        'usuario' => $s->usuario->nombre ?? '-',
                        'dias' => $s->dias_bloqueo,
                        'inicio' => $s->fecha_inicio,
                        'fin' => $s->fecha_fin,
                        'tipo' => $s->tipo
                    ];
                });
                break;
            case 'libros':
                $query = \App\Models\Prestamo::with('libro')
                    ->whereBetween('fecha_prestamo', [$inicio, $fin])
                    ->selectRaw('id_libro, count(*) as total')
                    ->groupBy('id_libro')
                    ->orderByDesc('total');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($p) {
                    return [
                        'libro' => $p->libro->titulo ?? '-',
                        'total' => $p->total
                    ];
                });
                break;
        }

        // Handle Excel export
        if ($export) {
            return $this->exportToExcel($resultados, $tipo, $inicio, $fin);
        }

        return response()->json(['resultados' => $resultados]);
    }

    private function exportToExcel($data, $tipo, $inicio, $fin)
    {
        $filename = "reporte_{$tipo}_" . date('Y-m-d') . ".csv";

        // Create CSV content
        $csvContent = '';

        // Add headers based on report type
        switch ($tipo) {
            case 'prestamos':
                $csvContent .= "ID,Libro,Usuario,Fecha Préstamo,Estado\n";
                break;
            case 'devoluciones':
                $csvContent .= "ID,Libro,Usuario,Fecha Devolución,Estado Libro\n";
                break;
            case 'usuarios':
                $csvContent .= "ID,Nombre,Apellido,Email,Fecha Registro\n";
                break;
            case 'sanciones':
                $csvContent .= "ID,Usuario,Días,Fecha Inicio,Fecha Fin,Tipo\n";
                break;
            case 'libros':
                $csvContent .= "Libro,Total Préstamos\n";
                break;
        }

        // Add data rows
        foreach ($data as $row) {
            $csvRow = [];
            foreach ($row as $value) {
                // Escape quotes and wrap in quotes if contains comma
                $value = str_replace('"', '""', $value);
                if (strpos($value, ',') !== false || strpos($value, '"') !== false) {
                    $value = '"' . $value . '"';
                }
                $csvRow[] = $value;
            }
            $csvContent .= implode(',', $csvRow) . "\n";
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0',
        ]);
    }

    private function exportSancionesToCSV($sanciones)
    {
        $filename = "sanciones_" . date('Y-m-d') . ".csv";

        // Create CSV content with UTF-8 BOM for proper Excel encoding
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvContent .= "ID,Usuario,Email,Tipo,Días Bloqueo,Fecha Inicio,Fecha Fin,Estado,Observaciones\n";

        foreach ($sanciones as $sancion) {
            $csvRow = [
                $sancion->id_sancion,
                '"' . str_replace('"', '""', $sancion->usuario->nombre ?? 'Usuario eliminado') . '"',
                '"' . str_replace('"', '""', $sancion->usuario->email ?? '') . '"',
                '"' . str_replace('"', '""', $sancion->tipo) . '"',
                $sancion->dias_bloqueo,
                $sancion->fecha_inicio,
                $sancion->fecha_fin ?? '',
                '"' . str_replace('"', '""', $sancion->estado) . '"',
                '"' . str_replace('"', '""', $sancion->observaciones ?? '') . '"'
            ];
            $csvContent .= implode(',', $csvRow) . "\n";
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0',
        ]);
    }

    // Método para gestionar sanciones
    public function gestionarSancion(Request $request, $accion, $id = null)
    {
        try {
            switch ($accion) {
                case 'levantar':
                    $sancion = \App\Models\Sancion::findOrFail($id);
                    $sancion->update([
                        'estado' => 'levantada',
                        'fecha_fin' => now()
                    ]);
                    return response()->json(['success' => true, 'message' => 'Sanción levantada correctamente']);

                case 'eliminar':
                    $sancion = \App\Models\Sancion::findOrFail($id);
                    $sancion->delete();
                    return response()->json(['success' => true, 'message' => 'Sanción eliminada correctamente']);

                case 'levantar_vencidas':
                    $sancionesVencidas = \App\Models\Sancion::where('estado', 'activa')
                        ->where('fecha_fin', '<', now())
                        ->update(['estado' => 'cumplida']);
                    return response()->json(['success' => true, 'message' => "Se levantaron {$sancionesVencidas} sanciones vencidas"]);

                case 'crear':
                    $request->validate([
                        'id_usuario' => 'required|exists:usuario,id_usuario',
                        'tipo' => 'required|string',
                        'dias_bloqueo' => 'required|integer|min:1',
                        'observaciones' => 'required|string'
                    ]);

                    $sancion = \App\Models\Sancion::create([
                        'id_usuario' => $request->id_usuario,
                        'tipo' => $request->tipo,
                        'dias_bloqueo' => $request->dias_bloqueo,
                        'fecha_inicio' => now(),
                        'fecha_fin' => now()->addDays($request->dias_bloqueo),
                        'estado' => 'activa',
                        'observaciones' => $request->observaciones
                    ]);

                    return response()->json(['success' => true, 'message' => 'Sanción aplicada correctamente', 'sancion' => $sancion]);

                case 'listar_sanciones':
                    $query = \App\Models\Sancion::with('usuario');

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
                                'observaciones' => $sancion->observaciones ?? ''
                            ];
                        });

                    return response()->json(['sanciones' => $sanciones]);

                case 'exportar_sanciones':
                    $query = \App\Models\Sancion::with('usuario');

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

                    $sanciones = $query->orderBy('created_at', 'desc')->get();

                    return $this->exportSancionesToCSV($sanciones);

                case 'metricas_sanciones':
                    return response()->json([
                        'activas' => \App\Models\Sancion::where('estado', 'activa')->count(),
                        'cumplidas_mes' => \App\Models\Sancion::where('estado', 'cumplida')
                            ->whereMonth('fecha_fin', now()->month)
                            ->count(),
                        'usuarios_sancionados' => \App\Models\Sancion::distinct('id_usuario')->count(),
                        'promedio_dias' => \App\Models\Sancion::avg('dias_bloqueo') ?? 0
                    ]);

                default:
                    return response()->json(['success' => false, 'message' => 'Acción no válida'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()], 500);
        }
    }

    public function tabla(Request $request)
    {
        $tipo = $request->tipo ?? 'prestamos';
        $inicio = $request->inicio ?? now()->subMonths(6)->startOfMonth()->toDateString();
        $fin = $request->fin ?? now()->endOfMonth()->toDateString();
        $export = $request->has('export') || $request->formato === 'excel';

        $resultados = [];
        switch ($tipo) {
            case 'prestamos':
                $query = \App\Models\Prestamo::with('usuario', 'libro')
                    ->whereBetween('fecha_prestamo', [$inicio, $fin])
                    ->orderBy('fecha_prestamo', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($p) {
                    return [
                        'id' => $p->id_prestamo,
                        'libro' => $p->libro->titulo ?? '-',
                        'usuario' => $p->usuario->nombre ?? '-',
                        'fecha' => $p->fecha_prestamo,
                        'estado' => ucfirst($p->estado)
                    ];
                });
                break;
            case 'devoluciones':
                $query = \App\Models\Devolucion::with('prestamo.usuario', 'prestamo.libro')
                    ->whereBetween('fecha_devolucion', [$inicio, $fin])
                    ->orderBy('fecha_devolucion', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($d) {
                    return [
                        'id' => $d->id_devolucion,
                        'libro' => $d->prestamo->libro->titulo ?? '-',
                        'usuario' => $d->prestamo->usuario->nombre ?? '-',
                        'fecha' => $d->fecha_devolucion,
                        'estado' => ucfirst($d->estado_libro)
                    ];
                });
                break;
            case 'usuarios':
                $query = \App\Models\Usuario::whereBetween('created_at', [$inicio, $fin])
                    ->orderBy('created_at', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($u) {
                    return [
                        'id' => $u->id_usuario,
                        'nombre' => $u->nombre,
                        'apellido' => $u->apellido,
                        'email' => $u->email,
                        'fecha' => $u->created_at->format('Y-m-d')
                    ];
                });
                break;
            case 'sanciones':
                $query = \App\Models\Sancion::with('usuario')
                    ->whereBetween('fecha_inicio', [$inicio, $fin])
                    ->orderBy('fecha_inicio', 'desc');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($s) {
                    return [
                        'id' => $s->id_sancion,
                        'usuario' => $s->usuario->nombre ?? '-',
                        'dias' => $s->dias_bloqueo,
                        'inicio' => $s->fecha_inicio,
                        'fin' => $s->fecha_fin,
                        'estado' => ucfirst($s->estado)
                    ];
                });
                break;
            case 'libros':
                $query = \App\Models\Prestamo::with('libro')
                    ->selectRaw('id_libro, count(*) as total')
                    ->whereBetween('fecha_prestamo', [$inicio, $fin])
                    ->groupBy('id_libro')
                    ->orderByDesc('total');

                if (!$export) $query->limit(20);

                $resultados = $query->get()->map(function($p) {
                    return [
                        'id' => $p->id_libro,
                        'libro' => $p->libro->titulo ?? '-',
                        'autor' => $p->libro->autor ?? '-',
                        'total' => $p->total
                    ];
                });
                break;
        }

        // Si es exportación, generar CSV
        if ($export) {
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
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($data, $tipo) {
            $file = fopen('php://output', 'w');

            // Agregar BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers según el tipo
            switch ($tipo) {
                case 'prestamos':
                    fputcsv($file, ['ID', 'Libro', 'Usuario', 'Fecha Préstamo', 'Estado']);
                    break;
                case 'devoluciones':
                    fputcsv($file, ['ID', 'Libro', 'Usuario', 'Fecha Devolución', 'Estado']);
                    break;
                case 'usuarios':
                    fputcsv($file, ['ID', 'Nombre', 'Apellido', 'Email', 'Fecha Registro']);
                    break;
                case 'sanciones':
                    fputcsv($file, ['ID', 'Usuario', 'Días', 'Fecha Inicio', 'Fecha Fin', 'Estado']);
                    break;
                case 'libros':
                    fputcsv($file, ['ID', 'Libro', 'Autor', 'Total Préstamos']);
                    break;
            }

            // Datos
            foreach ($data as $row) {
                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
