<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Administrador;
use App\Models\Trabajador;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsuarioController extends Controller
{
    // Listar usuarios (para el admin)
    public function index()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    // Mostrar formulario de registro
    public function showRegistro()
    {
        if (session()->has('usuario_id') || session()->has('admin_id') || session()->has('trabajador_id')) {
            return redirect()->route('login');
        }
        return view('registro');
    }

    // Procesar registro
    public function store(Request $request)
    {
        if (session()->has('usuario_id') || session()->has('admin_id') || session()->has('trabajador_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:usuario,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->fecha_registro = now();
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return redirect()->route('login')->with('success', 'Usuario creado correctamente.');
    }

    // Mostrar formulario de login
    public function showLogin()
    {
        if (session()->has('usuario_id')) {
            return redirect()->route('inicio');
        }
        if (session()->has('admin_id')) {
            return redirect()->route('admin.panel');
        }
        if (session()->has('trabajador_id')) {
            return redirect()->route('bibliotecario.dashboard');
        }
        return view('login');
    }

    // Iniciar sesión
    public function login(Request $request)
    {
        if (session()->has('usuario_id') || session()->has('admin_id') || session()->has('trabajador_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Buscar usuario por email
        $usuario = Usuario::where('email', $request->username)->first();
        if ($usuario && Hash::check($request->password, $usuario->password)) {
            session(['usuario_id' => $usuario->id_usuario, 'usuario_nombre' => $usuario->nombre]);
            return redirect()->route('inicio')->with('success', '¡Bienvenido!');
        }

        // Buscar administrador
        $admin = Administrador::where('usuario', $request->username)
            ->orWhere('correo', $request->username)
            ->first();
        if ($admin && Hash::check($request->password, $admin->clave)) {
            session(['admin_id' => $admin->id_admin, 'admin_nombre' => $admin->nombre]);
            return redirect()->route('admin.panel')->with('success', '¡Bienvenido Administrador!');
        }

        // Buscar trabajador
        $trabajador = Trabajador::where('usuario', $request->username)
            ->orWhere('email', $request->username)
            ->first();
        if ($trabajador && Hash::check($request->password, $trabajador->password)) {
            session(['trabajador_id' => $trabajador->id_trabajador, 'trabajador_nombre' => $trabajador->nombre]);
            return redirect()->route('bibliotecario.dashboard')->with('success', '¡Bienvenido Bibliotecario!');
        }

        return back()->withErrors(['login' => 'Credenciales incorrectas'])->withInput();
    }

    // Cerrar sesión
    public function logout()
    {
        session()->forget(['usuario_id', 'usuario_nombre', 'admin_id', 'admin_nombre', 'trabajador_id', 'trabajador_nombre']);
        session()->flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        // Elimina los préstamos asociados
        $usuario->prestamos()->delete();
        $usuario->delete();
        return redirect()->route('admin.panel')->with('success', 'Usuario eliminado correctamente.');
    }
    public function historial(Request $request)
    {
        $userId = session('usuario_id');
        $query = \App\Models\Prestamo::with('libro')
            ->where('id_usuario', $userId);

        // Filtro estado
        $estados = $request->estado ?? ['en_prestamo','devuelto','retrasado','pendiente','denegado'];
        $query->where(function($q) use ($estados) {
            foreach ($estados as $estado) {
                if ($estado == 'en_prestamo') $q->orWhere('estado', 'activo');
                if ($estado == 'devuelto') $q->orWhere('estado', 'entregado');
                if ($estado == 'retrasado') $q->orWhere('estado', 'retraso');
                if ($estado == 'pendiente') $q->orWhere('estado', 'pendiente');
                if ($estado == 'denegado') $q->orWhere('estado', 'denegado');
            }
        });

        // Filtro rango fechas
        if ($request->filled('rango')) {
            $dias = (int) $request->rango;
            $query->where('fecha_prestamo', '>=', now()->subDays($dias));
        }

        // Filtro búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('libro', function($q) use ($buscar) {
                $q->where('titulo', 'like', "%$buscar%");
            });
        }

        $prestamos = $query->orderByDesc('fecha_prestamo')->paginate(6);

        // Cambia 'usuario.historial' por 'historial'
        return view('historial', compact('prestamos'));
    }
}
