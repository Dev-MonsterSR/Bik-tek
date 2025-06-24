<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Sancion;
use App\Models\Usuario;
use Carbon\Carbon;
use PDF;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,bibliotecario']);
    }

    public function index()
    {
        return view('reportes.index');
    }

    public function prestamos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth());

        $prestamos = Prestamo::with(['usuario', 'libro'])
            ->whereBetween('fecha_prestamo', [$fechaInicio, $fechaFin])
            ->get();

        $estadisticas = [
            'total_prestamos' => $prestamos->count(),
            'prestamos_activos' => $prestamos->where('estado', 'activo')->count(),
            'prestamos_devueltos' => $prestamos->where('estado', 'devuelto')->count(),
            'prestamos_retrasados' => $prestamos->where('estado', 'retrasado')->count(),
            'libros_mas_prestados' => $this->getLibrosMasPrestados($fechaInicio, $fechaFin),
            'usuarios_mas_activos' => $this->getUsuariosMasActivos($fechaInicio, $fechaFin)
        ];

        if ($request->has('exportar_pdf')) {
            $pdf = PDF::loadView('reportes.prestamos-pdf', [
                'prestamos' => $prestamos,
                'estadisticas' => $estadisticas,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin
            ]);
            return $pdf->download('reporte-prestamos.pdf');
        }

        return view('reportes.prestamos', compact('prestamos', 'estadisticas', 'fechaInicio', 'fechaFin'));
    }

    public function sanciones(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth());

        $sanciones = Sancion::with(['usuario'])
            ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->get();

        $estadisticas = [
            'total_sanciones' => $sanciones->count(),
            'sanciones_activas' => $sanciones->where('estado', 'activa')->count(),
            'sanciones_por_tipo' => [
                'retraso' => $sanciones->where('tipo', 'retraso')->count(),
                'daño' => $sanciones->where('tipo', 'daño')->count(),
                'perdida' => $sanciones->where('tipo', 'perdida')->count()
            ]
        ];

        if ($request->has('exportar_pdf')) {
            $pdf = PDF::loadView('reportes.sanciones-pdf', [
                'sanciones' => $sanciones,
                'estadisticas' => $estadisticas,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin
            ]);
            return $pdf->download('reporte-sanciones.pdf');
        }

        return view('reportes.sanciones', compact('sanciones', 'estadisticas', 'fechaInicio', 'fechaFin'));
    }

    private function getLibrosMasPrestados($fechaInicio, $fechaFin)
    {
        return Libro::withCount(['prestamos' => function($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_prestamo', [$fechaInicio, $fechaFin]);
        }])
        ->orderBy('prestamos_count', 'desc')
        ->take(5)
        ->get();
    }

    private function getUsuariosMasActivos($fechaInicio, $fechaFin)
    {
        return Usuario::withCount(['prestamos' => function($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_prestamo', [$fechaInicio, $fechaFin]);
        }])
        ->orderBy('prestamos_count', 'desc')
        ->take(5)
        ->get();
    }
}
