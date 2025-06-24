<?php
/**
 * Script para crear datos de prueba para el panel del bibliotecario
 * Ejecutar: php crear_datos_prueba.php
 */

require_once 'vendor/autoload.php';

use App\Models\Usuario;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Configurar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CREANDO DATOS DE PRUEBA PARA EL PANEL DEL BIBLIOTECARIO ===\n\n";

try {
    // 1. Crear usuarios de prueba
    echo "1. Creando usuarios de prueba...\n";
      $usuarios = [
        [
            'nombre' => 'Juan',
            'apellido' => 'Pérez García',
            'email' => 'juan.perez@tecsup.edu.pe',
            'fecha_registro' => now(),
            'password' => bcrypt('password123')
        ],
        [
            'nombre' => 'María',
            'apellido' => 'González López',
            'email' => 'maria.gonzalez@tecsup.edu.pe',
            'fecha_registro' => now(),
            'password' => bcrypt('password123')
        ],
        [
            'nombre' => 'Carlos',
            'apellido' => 'Rodríguez Silva',
            'email' => 'carlos.rodriguez@tecsup.edu.pe',
            'fecha_registro' => now(),
            'password' => bcrypt('password123')
        ],
        [
            'nombre' => 'Ana',
            'apellido' => 'Martínez Torres',
            'email' => 'ana.martinez@tecsup.edu.pe',
            'fecha_registro' => now(),
            'password' => bcrypt('password123')
        ]
    ];

    foreach ($usuarios as $userData) {
        $usuario = Usuario::firstOrCreate(
            ['email' => $userData['email']],
            $userData
        );
        echo "  ✓ Usuario creado: {$usuario->nombre} {$usuario->apellido}\n";
    }

    // 2. Crear categorías de prueba
    echo "\n2. Creando categorías de prueba...\n";

    $categorias = [
        ['nombre' => 'Programación', 'descripcion' => 'Libros de programación y desarrollo'],
        ['nombre' => 'Ingeniería', 'descripcion' => 'Libros de ingeniería general'],
        ['nombre' => 'Matemáticas', 'descripcion' => 'Libros de matemáticas y estadística'],
        ['nombre' => 'Electrónica', 'descripcion' => 'Libros de electrónica y sistemas digitales']
    ];

    foreach ($categorias as $catData) {
        $categoria = Categoria::firstOrCreate(
            ['nombre' => $catData['nombre']],
            $catData
        );
        echo "  ✓ Categoría creada: {$categoria->nombre}\n";
    }

    // 3. Crear libros de prueba
    echo "\n3. Creando libros de prueba...\n";
      $libros = [
        [
            'codigo' => 'LIB001',
            'titulo' => 'Fundamentos de Programación en Java',
            'autor' => 'John Smith',
            'cantidad' => 5,
            'disponibles' => 3,
            'estado' => 'disponible',
            'categoria_id' => 1
        ],
        [
            'codigo' => 'LIB002',
            'titulo' => 'Ingeniería de Software Moderna',
            'autor' => 'Maria Johnson',
            'cantidad' => 4,
            'disponibles' => 2,
            'estado' => 'disponible',
            'categoria_id' => 1
        ],
        [
            'codigo' => 'LIB003',
            'titulo' => 'Electrónica Digital Avanzada',
            'autor' => 'Roberto García',
            'cantidad' => 3,
            'disponibles' => 1,
            'estado' => 'disponible',
            'categoria_id' => 4
        ],
        [
            'codigo' => 'LIB004',
            'titulo' => 'Cálculo Diferencial e Integral',
            'autor' => 'Ana López',
            'cantidad' => 6,
            'disponibles' => 4,
            'estado' => 'disponible',
            'categoria_id' => 3
        ],
        [
            'codigo' => 'LIB005',
            'titulo' => 'Estructuras de Datos en Python',
            'autor' => 'Carlos Mendoza',
            'cantidad' => 3,
            'disponibles' => 1,
            'estado' => 'disponible',
            'categoria_id' => 1
        ]
    ];

    foreach ($libros as $libroData) {
        $libro = Libro::firstOrCreate(
            ['codigo' => $libroData['codigo']],
            $libroData
        );
        echo "  ✓ Libro creado: {$libro->titulo}\n";
    }

    // 4. Crear préstamos de prueba
    echo "\n4. Creando préstamos de prueba...\n";

    $usuarios = Usuario::all();
    $libros = Libro::all();

    // Préstamos pendientes (para aprobar)
    $prestamosPendientes = [
        [
            'usuario_email' => 'juan.perez@tecsup.edu.pe',
            'libro_codigo' => 'LIB001',
            'estado' => 'pendiente'
        ],
        [
            'usuario_email' => 'maria.gonzalez@tecsup.edu.pe',
            'libro_codigo' => 'LIB002',
            'estado' => 'pendiente'
        ],
        [
            'usuario_email' => 'juan.perez@tecsup.edu.pe',
            'libro_codigo' => 'LIB003',
            'estado' => 'pendiente'
        ],
        [
            'usuario_email' => 'carlos.rodriguez@tecsup.edu.pe',
            'libro_codigo' => 'LIB004',
            'estado' => 'pendiente'
        ]
    ];

    foreach ($prestamosPendientes as $prestamoData) {
        $usuario = Usuario::where('email', $prestamoData['usuario_email'])->first();
        $libro = Libro::where('codigo', $prestamoData['libro_codigo'])->first();

        if ($usuario && $libro) {
            $prestamo = Prestamo::create([
                'id_usuario' => $usuario->id_usuario,
                'id_libro' => $libro->id_libro,
                'fecha_prestamo' => now(),
                'fecha_devolucion' => now()->addDays(7),
                'estado' => $prestamoData['estado']
            ]);
            echo "  ✓ Préstamo pendiente creado: {$libro->titulo} para {$usuario->nombre}\n";
        }
    }

    // Préstamos activos (para devolución)
    $prestamosActivos = [
        [
            'usuario_email' => 'maria.gonzalez@tecsup.edu.pe',
            'libro_codigo' => 'LIB005',
            'dias_atras' => 2 // En plazo
        ],
        [
            'usuario_email' => 'ana.martinez@tecsup.edu.pe',
            'libro_codigo' => 'LIB001',
            'dias_atras' => -3 // Atrasado
        ],
        [
            'usuario_email' => 'carlos.rodriguez@tecsup.edu.pe',
            'libro_codigo' => 'LIB002',
            'dias_atras' => 1 // En plazo
        ]
    ];

    foreach ($prestamosActivos as $prestamoData) {
        $usuario = Usuario::where('email', $prestamoData['usuario_email'])->first();
        $libro = Libro::where('codigo', $prestamoData['libro_codigo'])->first();

        if ($usuario && $libro) {
            $fechaPrestamo = now()->subDays(5);
            $fechaDevolucion = now()->addDays($prestamoData['dias_atras']);

            $prestamo = Prestamo::create([
                'id_usuario' => $usuario->id_usuario,
                'id_libro' => $libro->id_libro,
                'fecha_prestamo' => $fechaPrestamo,
                'fecha_devolucion' => $fechaDevolucion,
                'estado' => 'activo'
            ]);

            $estado = $prestamoData['dias_atras'] > 0 ? 'en plazo' : 'atrasado';
            echo "  ✓ Préstamo activo creado: {$libro->titulo} para {$usuario->nombre} ({$estado})\n";
        }
    }

    echo "\n=== DATOS DE PRUEBA CREADOS EXITOSAMENTE ===\n";
    echo "Ahora puedes probar el panel del bibliotecario con:\n";
    echo "- Usuarios con préstamos pendientes de aprobación\n";
    echo "- Usuarios con devoluciones en curso (algunos atrasados)\n";
    echo "- Búsqueda funcional por nombre y email\n";
    echo "- Filtros por tipo de solicitud\n\n";

    echo "Para acceder al panel: http://localhost:8000/bibliotecario/dashboard\n";
    echo "Primero debes hacer login como bibliotecario.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
}
?>
