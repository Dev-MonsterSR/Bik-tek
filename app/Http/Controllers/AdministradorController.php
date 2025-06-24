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
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
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

        if ($tipo === 'kpis') {
            // Calcular KPIs reales del sistema
            $totalLibros = Libro::sum('cantidad');
            $disponibles = Libro::sum('disponibles');
            $librosDisponiblesPct = $totalLibros > 0 ? round(($disponibles / $totalLibros) * 100) : 0;

            $prestamosActivos = Prestamo::where('estado', 'activo')->count();
            $totalPrestamos = Prestamo::count();
            $prestamosActivosPct = $totalPrestamos > 0 ? round(($prestamosActivos / $totalPrestamos) * 100) : 0;

            $usuariosConPrestamos = Usuario::whereHas('prestamos')->count();
            $totalUsuarios = Usuario::count();
            $usuariosActivosPct = $totalUsuarios > 0 ? round(($usuariosConPrestamos / $totalUsuarios) * 100) : 0;

            $devoluciones = Devolucion::count();
            $prestamosCompletados = Prestamo::where('estado', 'completado')->count();
            $tasaDevolucionPct = $prestamosCompletados > 0 ? round(($devoluciones / $prestamosCompletados) * 100) : 0;

            return response()->json([
                'libros_disponibles_pct' => $librosDisponiblesPct,
                'prestamos_activos_pct' => $prestamosActivosPct,
                'usuarios_activos_pct' => $usuariosActivosPct,
                'tasa_devolucion_pct' => $tasaDevolucionPct
            ]);
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
            $resultados = Usuario::selectRaw("DATE_FORMAT(fecha_registro, '{$groupFormat}') as periodo, COUNT(*) as total")
                ->whereBetween('fecha_registro', [$inicio, $fin])
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
                $resultados = Usuario::whereBetween('fecha_registro', [$inicio, $fin])
                    ->orderBy('fecha_registro', 'desc')
                    ->get()
                    ->map(function($usuario) {
                        return [
                            'nombre' => $usuario->nombre,
                            'apellido' => $usuario->apellido,
                            'email' => $usuario->email,
                            'fecha' => Carbon::parse($usuario->fecha_registro)->format('d/m/Y')
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

            case 'inventario':
                $query = Libro::with('categoria');
                // Para inventario, las fechas no aplican pero podemos filtrar por fecha de registro
                if ($inicio && $fin) {
                    $query->whereBetween('created_at', [$inicio, $fin]);
                }
                $resultados = $query->orderBy('titulo')
                    ->get()
                    ->map(function($libro) {
                        return [
                            'codigo' => $libro->codigo,
                            'titulo' => $libro->titulo,
                            'autor' => $libro->autor ?? 'No especificado',
                            'categoria' => $libro->categoria->nombre ?? 'Sin categoría',
                            'cantidad' => $libro->cantidad,
                            'disponibles' => $libro->disponibles,
                            'estado' => ucfirst($libro->estado)
                        ];
                    });
                break;
        }

        // Si se solicita exportación
        if ($request->export) {
            $formato = $request->formato ?? 'csv';
            $fechaInicio = $request->inicio;
            $fechaFin = $request->fin;

            return $this->exportData($resultados, $tipo, $formato, $fechaInicio, $fechaFin);
        }

        return response()->json(['resultados' => $resultados]);
    }

    private function exportData($data, $tipo, $formato = 'csv', $fechaInicio = null, $fechaFin = null)
    {
        $fechaTexto = '';
        if ($fechaInicio && $fechaFin) {
            $fechaTexto = "_desde_{$fechaInicio}_hasta_{$fechaFin}";
        } elseif ($fechaInicio) {
            $fechaTexto = "_desde_{$fechaInicio}";
        } elseif ($fechaFin) {
            $fechaTexto = "_hasta_{$fechaFin}";
        }

        $filename = "reporte_{$tipo}" . $fechaTexto . "_" . date('Y-m-d');

        if ($formato === 'excel') {
            return $this->exportToExcel($data, $tipo, $filename);
        } else {
            return $this->exportToCSV($data, $tipo, $filename);
        }
    }

    private function exportToExcel($data, $tipo, $filename)
    {
        try {
            $filename .= '.xlsx';

            $writer = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createXLSXWriter();

            // Configurar archivo temporal
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_export');
            $writer->openToFile($tempFile);

            // Crear encabezados según tipo con formato
            $headers = $this->getReportHeaders($tipo);
            $headerRow = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($headers);

            // Aplicar estilo a los encabezados
            $headerStyle = (new StyleBuilder())
                ->setFontBold()
                ->setBackgroundColor('E6E6E6')
                ->build();
            $headerRow->setStyle($headerStyle);

            $writer->addRow($headerRow);

            // Agregar datos
            foreach ($data as $index => $row) {
                $rowData = [$index + 1];
                foreach ($row as $value) {
                    // Formatear fechas si es necesario
                    if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
                        $rowData[] = date('d/m/Y', strtotime($value));
                    } else {
                        $rowData[] = $value;
                    }
                }

                $dataRow = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($rowData);
                $writer->addRow($dataRow);
            }

            $writer->close();

            // Configurar headers para descarga
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Content-Length' => filesize($tempFile),
            ];

            return response()->stream(function() use ($tempFile) {
                readfile($tempFile);
                unlink($tempFile); // Limpiar archivo temporal
            }, 200, $headers);

        } catch (\Exception $e) {
            // Si falla Excel, fallback a CSV
            return $this->exportToCSV($data, $tipo, $filename);
        }
    }

    private function exportToCSV($data, $tipo, $filename)
    {
        $filename .= '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data, $tipo) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers según tipo
            $headers = $this->getReportHeaders($tipo);
            fputcsv($file, $headers);

            // Datos
            foreach ($data as $index => $row) {
                $csvRow = [$index + 1];
                foreach ($row as $value) {
                    // Formatear fechas
                    if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
                        $csvRow[] = date('d/m/Y', strtotime($value));
                    } else {
                        $csvRow[] = $value;
                    }
                }
                fputcsv($file, $csvRow);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getReportHeaders($tipo)
    {
        switch ($tipo) {
            case 'prestamos':
                return ['#', 'Libro', 'Usuario', 'Fecha Préstamo', 'Estado'];
            case 'devoluciones':
                return ['#', 'Libro', 'Usuario', 'Fecha Devolución', 'Estado'];
            case 'usuarios':
                return ['#', 'Nombre', 'Apellido', 'Email', 'Fecha Registro'];
            case 'sanciones':
                return ['#', 'Usuario', 'Días Sanción', 'Fecha Inicio', 'Fecha Fin'];
            case 'libros':
                return ['#', 'Libro', 'Autor', 'Préstamos Totales'];
            case 'inventario':
                return ['#', 'Código', 'Título', 'Autor', 'Categoría', 'Cantidad', 'Disponibles', 'Estado'];
            default:
                return ['#', 'Datos'];
        }
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

    // Método para obtener detalles de un trabajador (AJAX)
    public function getDetallesTrabajador($id)
    {
        try {
            $trabajador = Trabajador::find($id);

            if (!$trabajador) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trabajador no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'trabajador' => [
                    'id_trabajador' => $trabajador->id_trabajador,
                    'usuario' => $trabajador->usuario,
                    'nombre' => $trabajador->nombre,
                    'email' => $trabajador->email,
                    'dni' => $trabajador->dni,
                    'telefono' => $trabajador->telefono,
                    'direccion' => $trabajador->direccion,
                    'fecha_registro' => $trabajador->created_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles del trabajador: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener detalles de un usuario (AJAX)
    public function getDetallesUsuario($id)
    {
        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'usuario' => [
                    'id_usuario' => $usuario->id_usuario,
                    'nombre' => $usuario->nombre,
                    'apellido' => $usuario->apellido,
                    'email' => $usuario->email,
                    'dni' => $usuario->dni,
                    'codigo_estudiante' => $usuario->codigo_estudiante,
                    'fecha_registro' => $usuario->fecha_registro,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles del usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}
