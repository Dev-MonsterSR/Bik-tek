<?php
/**
 * Script de verificación de problemas comunes después de migración
 * Ejecutar: php verificar_sistema.php
 */

echo "=== VERIFICACIÓN DEL SISTEMA POST-MIGRACIÓN ===\n\n";

// 1. Verificar directorios críticos
echo "1. Verificando directorios...\n";
$directorios = [
    'public/img/portadas' => 'Directorio de portadas',
    'storage/app/public' => 'Storage público',
    'storage/framework/cache' => 'Cache del framework',
    'storage/logs' => 'Logs del sistema',
    'bootstrap/cache' => 'Cache de bootstrap'
];

foreach ($directorios as $dir => $descripcion) {
    if (is_dir($dir)) {
        echo "✅ $descripcion: OK\n";
        echo "   Permisos: " . substr(sprintf('%o', fileperms($dir)), -4) . "\n";
    } else {
        echo "❌ $descripcion: NO EXISTE\n";
        echo "   Creando directorio...\n";
        mkdir($dir, 0755, true);
    }
}

// 2. Verificar archivos de configuración
echo "\n2. Verificando configuración...\n";
if (file_exists('.env')) {
    echo "✅ Archivo .env existe\n";
} else {
    echo "❌ Archivo .env NO EXISTE\n";
}

if (file_exists('.env.digitalocean')) {
    echo "✅ Plantilla .env.digitalocean creada\n";
} else {
    echo "❌ Plantilla .env.digitalocean NO EXISTE\n";
}

// 3. Verificar helper personalizado
echo "\n3. Verificando helpers...\n";
if (file_exists('app/Helpers/ImageHelper.php')) {
    echo "✅ Helper de imágenes existe\n";

    // Verificar si está registrado en composer.json
    $composer = json_decode(file_get_contents('composer.json'), true);
    if (isset($composer['autoload']['files']) && in_array('app/Helpers/ImageHelper.php', $composer['autoload']['files'])) {
        echo "✅ Helper registrado en composer.json\n";
    } else {
        echo "❌ Helper NO registrado en composer.json\n";
    }
} else {
    echo "❌ Helper de imágenes NO EXISTE\n";
}

// 4. Verificar imágenes de muestra
echo "\n4. Verificando imágenes...\n";
$imagenesEjemplo = [
    'public/img/Libro1.jpg' => 'Imagen por defecto',
    'public/img/LocalB/logo.jpg' => 'Logo del sistema'
];

foreach ($imagenesEjemplo as $imagen => $descripcion) {
    if (file_exists($imagen)) {
        echo "✅ $descripcion: OK\n";
    } else {
        echo "⚠️ $descripcion: NO EXISTE (opcional)\n";
    }
}

// 5. Verificar base de datos (requiere conexión)
echo "\n5. Verificando base de datos...\n";
try {
    if (file_exists('database/database.sqlite')) {
        echo "✅ Base de datos SQLite encontrada\n";
    } else {
        echo "⚠️ Base de datos SQLite no encontrada (puede usar MySQL)\n";
    }
} catch (Exception $e) {
    echo "⚠️ No se pudo verificar la base de datos\n";
}

// 6. Verificar archivos críticos del sistema
echo "\n6. Verificando archivos del sistema...\n";
$archivos = [
    'artisan' => 'Artisan CLI',
    'composer.json' => 'Configuración Composer',
    'package.json' => 'Dependencias Node.js',
    'app/Http/Controllers/LibroController.php' => 'Controlador de libros',
    'resources/views/administrador.blade.php' => 'Vista de administrador'
];

foreach ($archivos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        echo "✅ $descripcion: OK\n";
    } else {
        echo "❌ $descripcion: NO EXISTE\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Si todos los elementos muestran ✅, el sistema está listo para migrar.\n";
echo "Si hay elementos con ❌, revisa la documentación de migración.\n";
echo "Los elementos con ⚠️ son opcionales pero recomendados.\n\n";

echo "Próximos pasos:\n";
echo "1. Ejecutar: composer dump-autoload\n";
echo "2. Subir archivos a Digital Ocean\n";
echo "3. Ejecutar script digitalocean-setup.sh en el servidor\n";
echo "4. Configurar variables de entorno\n";
echo "5. Probar todas las funcionalidades\n";
?>
