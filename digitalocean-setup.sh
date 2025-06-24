#!/bin/bash

# Script de configuración para Digital Ocean
echo "=== Configurando Sistema Biblioteca para Digital Ocean ==="

# 1. Crear directorios necesarios
echo "Creando directorios necesarios..."
mkdir -p public/img/portadas
mkdir -p storage/app/public/img/portadas
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# 2. Configurar permisos
echo "Configurando permisos..."
chmod -R 755 public/img
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 3. Crear enlace simbólico de storage
echo "Creando enlace simbólico de storage..."
php artisan storage:link

# 4. Limpiar y optimizar cache
echo "Optimizando aplicación..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

# 5. Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# 6. Configurar servidor web
echo "=== Configuración del servidor web ==="
echo "Asegúrate de:"
echo "1. Apuntar el DocumentRoot a la carpeta 'public/'"
echo "2. Configurar URL rewriting para Laravel"
echo "3. Configurar variables de entorno en el panel de Digital Ocean"
echo "4. Subir el contenido de .env.digitalocean como variables de entorno"

echo "=== Configuración completada ==="
