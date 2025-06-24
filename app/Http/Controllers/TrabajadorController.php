<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

    class TrabajadorController extends Controller
    {
        public function index()
        {
            $trabajadores = Trabajador::all();
            return view('admin.panel', compact('trabajadores'));
        }

        public function create()
        {
            return view('trabajadores.create');
        }

        public function store(Request $request)
    {
        // Validación mejorada
        $validated = $request->validate([
            'usuario' => 'required|unique:trabajadores|max:50',
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'email' => 'required|email|unique:trabajadores|max:100',
            'dni' => 'required|unique:trabajadores|max:15',
            'telefono' => 'nullable|max:20',
            'direccion' => 'nullable|max:255',
            'password' => 'required|min:6'
        ]);

        try {
            $trabajador = Trabajador::create([
                'usuario' => $validated['usuario'],
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'dni' => $validated['dni'],
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'],
                'password' => Hash::make($validated['password'])
            ]);

            // Respuesta para AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bibliotecario creado correctamente!',
                    'data' => $trabajador
                ]);
            }

            return redirect()->route('admin.panel')
                ->with('success', 'Bibliotecario creado correctamente!');

        } catch (\Exception $e) {
            // Respuesta para AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear: '.$e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error al crear: '.$e->getMessage());
        }
    }

    public function show(Trabajador $trabajador)
    {
        return view('trabajadores.show', compact('trabajador'));
    }

    public function edit(Trabajador $trabajador)
    {
        return view('trabajadores.edit', compact('trabajador'));
    }

    public function update(Request $request, Trabajador $trabajador)
    {
        $request->validate([
            'usuario' => 'required|unique:trabajadores,usuario,'.$trabajador->id_trabajador.',id_trabajador',
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:trabajadores,email,'.$trabajador->id_trabajador.',id_trabajador',
            'dni' => 'required|unique:trabajadores,dni,'.$trabajador->id_trabajador.',id_trabajador',
        ]);

        $data = $request->only(['usuario', 'nombre', 'apellido', 'email', 'dni', 'telefono', 'direccion']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $trabajador->update($data);

        return redirect()->route('admin.panel')->with('success', 'Trabajador actualizado correctamente.');
    }

    public function destroy(Trabajador $trabajador)
    {
        $trabajador->delete();
        return redirect()->route('admin.panel')->with('success', 'Trabajador eliminado correctamente.');
    }
}
