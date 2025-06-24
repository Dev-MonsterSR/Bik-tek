<?php

if (!function_exists('imagen_libro')) {
    /**
     * Genera la URL correcta para la portada de un libro
     */
    function imagen_libro($portada = null) {
        if (!$portada) {
            return asset('img/Libro1.jpg');
        }

        // Si la ruta ya incluye 'storage/', usar asset() directamente
        if (strpos($portada, 'storage/') === 0) {
            return asset($portada);
        }

        // Si la ruta comienza con 'img/', usar asset() directamente
        if (strpos($portada, 'img/') === 0) {
            return asset($portada);
        }

        // Si es solo el nombre del archivo, asumir que está en img/portadas/
        if (!str_contains($portada, '/')) {
            return asset('img/portadas/' . $portada);
        }

        // Por defecto, usar asset()
        return asset($portada);
    }
}

if (!function_exists('es_produccion')) {
    /**
     * Verifica si estamos en entorno de producción
     */
    function es_produccion() {
        return app()->environment('production');
    }
}

if (!function_exists('url_completa')) {
    /**
     * Genera URL completa con dominio para uso en APIs o emails
     */
    function url_completa($ruta = '') {
        $baseUrl = config('app.url');
        return rtrim($baseUrl, '/') . '/' . ltrim($ruta, '/');
    }
}
