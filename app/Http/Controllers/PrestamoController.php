<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Sancion;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    public function __construct()
    {
        // Removemos el middleware auth ya que usamos sesiones
    }

    public function index()
    {
        $prestamos = Prestamo::with(['libro', 'usuario'])
            ->where('id_usuario', session('usuario_id'))
            ->orderBy('fecha_prestamo', 'desc')
            ->paginate(10);

        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {
        $libros = Libro::where('disponibles', '>', 0)
            ->where('estado', 'disponible')
            ->get();
        return view('prestamos.create', compact('libros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_libro' => 'required|exists:libro,id_libro',
            'dias_prestamo' => 'required|integer|min:3|max:5',
            'modalidad' => 'required|in:presencial',
            'fecha_devolucion' => 'required|date|after:today',
        ]);

        $usuarioId = session('usuario_id');
        $libro = \App\Models\Libro::findOrFail($request->id_libro);
        if ($libro->disponibles <= 0) {
            return back()->with('error', 'El libro no está disponible en este momento.');
        }

        // Verificar sanción activa (por retraso u otro motivo)
        $sancionActiva = \App\Models\Sancion::where('id_usuario', $usuarioId)
            ->where('estado', 'activa')
            ->where('fecha_fin', '>', date('Y-m-d'))
            ->first();
        if ($sancionActiva) {
            return back()->with('error', 'Tienes una sanción activa hasta ' . date('d/m/Y', strtotime($sancionActiva->fecha_fin)));
        }

        // Contar préstamos activos y pendientes (no entregados ni denegados)
        $prestamosNoEntregados = \App\Models\Prestamo::where('id_usuario', $usuarioId)
            ->whereIn('estado', ['activo', 'pendiente'])
            ->count();
        if ($prestamosNoEntregados >= 3) {
            return back()->with('error', 'Solo puedes tener hasta 3 préstamos activos o pendientes a la vez.');
        }

        // Crear el préstamo en estado pendiente
        \App\Models\Prestamo::create([
            'id_libro' => $request->id_libro,
            'id_usuario' => $usuarioId,
            'fecha_prestamo' => date('Y-m-d'),
            'fecha_devolucion' => $request->fecha_devolucion,
            'estado' => 'pendiente'
        ]);

        return redirect()->route('inicio')->with('success', 'Solicitud de préstamo enviada correctamente. Espera la aprobación del bibliotecario.');
    }

    public function show($id)
    {
        $prestamo = Prestamo::with(['libro', 'usuario'])
            ->where('id_usuario', session('usuario_id'))
            ->findOrFail($id);

        return view('prestamos.show', compact('prestamo'));
    }

    public function edit($id)
    {
        $prestamo = Prestamo::findOrFail($id);
        if ($prestamo->id_usuario !== session('usuario_id')) {
            abort(403);
        }
        return view('prestamos.edit', compact('prestamo'));
    }

    public function update(Request $request, $id)
    {
        $prestamo = Prestamo::findOrFail($id);

        if ($prestamo->id_usuario !== session('usuario_id')) {
            abort(403);
        }

        $request->validate([
            'fecha_devolucion' => 'required|date|after:today',
            'estado' => 'required|in:activo,entregado,retraso,perdido',
        ]);

        $prestamo->update($request->all());

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $prestamo = Prestamo::findOrFail($id);

        if ($prestamo->id_usuario !== session('usuario_id')) {
            abort(403);
        }

        if ($prestamo->estado === 'activo') {
            return back()->with('error', 'No se puede eliminar un préstamo activo.');
        }

        $prestamo->delete();
        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo eliminado correctamente.');
    }
}


