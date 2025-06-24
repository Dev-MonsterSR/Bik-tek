<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Categoria;

class LibroController extends Controller
{
    // Página principal
    public function inicio()
    {
        $libros = Libro::with('categoria')->take(4)->get();
        return view('index', compact('libros'));
    }

    // Catálogo
    public function index(Request $request)
    {
        $query = Libro::with('categoria');

        // Filtro de búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'like', "%$buscar%")
                  ->orWhere('autor', 'like', "%$buscar%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Ordenar (igual que antes)
        switch ($request->ordenar) {
            case 'titulo_asc':
                $query->orderBy('titulo', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('titulo', 'desc');
                break;
            case 'autor_asc':
                $query->orderBy('autor', 'asc');
                break;
            case 'autor_desc':
                $query->orderBy('autor', 'desc');
                break;
            case 'anio_asc':
                $query->orderBy('anio_publicacion', 'asc');
                break;
            case 'anio_desc':
                $query->orderBy('anio_publicacion', 'desc');
                break;
            default:
                $query->orderBy('id_libro', 'desc');
        }

        $libros = $query->paginate(9);

        // Enviar categorías a la vista
        $categorias = \App\Models\Categoria::all();

        return view('catalogo', compact('libros', 'categorias'));
    }

    // Formulario de creación
    public function create()
    {
        $categorias = Categoria::all();
        return view('libros.create', compact('categorias'));
    }

    // Almacena libro
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'            => 'required|string|max:50|unique:libro,codigo',
            'titulo'            => 'required|string|max:255',
            'autor'             => 'nullable|string|max:150',
            'editorial'         => 'nullable|string|max:150',
            'anio_publicacion'  => 'nullable|integer|between:1901,' . date('Y'),
            'categoria_id'      => 'nullable|exists:categoria,id_categoria',
            'cantidad'          => 'required|integer|min:1',
            'disponibles'       => 'required|integer|min:0',
            'estado'            => 'required|in:disponible,prestado,dañado',
            'portada'           => 'nullable|image|max:2048',
        ]);

        try {
            // Procesar la portada si existe
            if ($request->hasFile('portada')) {
                $file = $request->file('portada');
                $nombre = 'portada_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Crear directorio si no existe
                $directorioPortadas = public_path('img/portadas');
                if (!file_exists($directorioPortadas)) {
                    mkdir($directorioPortadas, 0755, true);
                }

                // Mover archivo a public/img/portadas
                $file->move($directorioPortadas, $nombre);
                $validated['portada'] = 'img/portadas/' . $nombre;
            }

            // Si el campo año está vacío, ponerlo como null
            if (empty($validated['anio_publicacion'])) {
                $validated['anio_publicacion'] = null;
            }

            Libro::create($validated);

            // Si es AJAX, responde con JSON
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Libro creado correctamente.']);
            }

            return redirect()->route('libros.index')
                             ->with('success', 'Libro creado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->validator->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al guardar el libro: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'Ocurrió un error al guardar el libro: ' . $e->getMessage());
        }
    }

    // Muestra un libro
    public function show($id)
    {
        $libro = Libro::with('categoria')->findOrFail($id);
        return view('libros.show', compact('libro'));
    }

    // Formulario de edición
    public function edit($id)
    {
        $libro      = Libro::findOrFail($id);
        $categorias = Categoria::all();
        return view('libros.edit', compact('libro', 'categorias'));
    }

    // Actualiza libro
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'codigo'            => "required|string|max:50|unique:libro,codigo,{$id},id_libro",
                'titulo'            => 'required|string|max:255',
                'autor'             => 'nullable|string|max:150',
                'editorial'         => 'nullable|string|max:150',
                'anio_publicacion'  => 'nullable|integer|between:1901,' . date('Y'),
                'categoria_id'      => 'nullable|exists:categoria,id_categoria',
                'cantidad'          => 'required|integer|min:1',
                'disponibles'       => 'required|integer|min:0',
                'estado'            => 'required|in:disponible,prestado,dañado',
                'portada'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $libro = Libro::findOrFail($id);

        $data = $request->only([
            'codigo',
            'titulo',
            'autor',
            'editorial',
            'anio_publicacion',
            'categoria_id',
            'cantidad',
            'disponibles',
            'estado'
        ]);

        if (empty($data['anio_publicacion'])) {
            $data['anio_publicacion'] = null;
        }

        try {
            // Subida nueva de portada (si aplica)
            if ($request->hasFile('portada')) {
                // Eliminar portada anterior si existe
                if ($libro->portada && file_exists(public_path($libro->portada))) {
                    unlink(public_path($libro->portada));
                }

                $file = $request->file('portada');
                $filename = uniqid('portada_') . '.' . $file->getClientOriginalExtension();

                // Crear directorio si no existe
                $directorioPortadas = public_path('img/portadas');
                if (!file_exists($directorioPortadas)) {
                    mkdir($directorioPortadas, 0755, true);
                }

                $file->move($directorioPortadas, $filename);
                $data['portada'] = 'img/portadas/' . $filename;
            }

            $libro->update($data);

            // Si es una petición AJAX, devolver respuesta JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Libro actualizado correctamente.'
                ]);
            }

            // Redirige al panel de administrador (ajusta la ruta si es diferente)
            return redirect()->route('admin.panel')->with('success', 'Libro actualizado correctamente.');
        } catch (\Exception $e) {
            // Si es una petición AJAX, devolver error JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al actualizar el libro: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el libro: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $libro = Libro::findOrFail($id);

            // Verificar si el libro tiene préstamos activos usando el método del modelo
            if ($libro->tienePrestamosActivos()) {
                return redirect()->route('admin.panel')->with('error', 'No se puede eliminar el libro "' . $libro->titulo . '" porque tiene préstamos activos. Debe esperar a que se devuelvan todos los préstamos.');
            }

            // Si tiene préstamos entregados o denegados, los eliminamos primero
            if ($libro->prestamos()->count() > 0) {
                $libro->prestamos()->delete();
            }

            // Eliminar la imagen de portada si existe
            if ($libro->portada && file_exists(public_path($libro->portada))) {
                unlink(public_path($libro->portada));
            }

            $tituloLibro = $libro->titulo; // Guardar el título antes de eliminar
            $libro->delete();

            return redirect()->route('admin.panel')->with('success', 'El libro "' . $tituloLibro . '" ha sido eliminado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Error de base de datos (restricción de clave foránea)
            if ($e->getCode() === '23000') {
                return redirect()->route('admin.panel')->with('error', 'No se puede eliminar el libro porque está asociado a registros en la base de datos. Contacte al administrador del sistema.');
            }
            return redirect()->route('admin.panel')->with('error', 'Error de base de datos: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('admin.panel')->with('error', 'Ocurrió un error inesperado al eliminar el libro: ' . $e->getMessage());
        }
    }

    // Método AJAX para obtener datos de un libro para edición
    public function getEditData(Libro $libro)
    {
        return response()->json([
            'id_libro' => $libro->id_libro,
            'codigo' => $libro->codigo,
            'titulo' => $libro->titulo,
            'autor' => $libro->autor,
            'editorial' => $libro->editorial,
            'anio_publicacion' => $libro->anio_publicacion,
            'categoria_id' => $libro->categoria_id,
            'cantidad' => $libro->cantidad,
            'disponibles' => $libro->disponibles,
            'estado' => $libro->estado,
            'portada' => $libro->portada ? imagen_libro($libro->portada) : null
        ]);
    }
}
