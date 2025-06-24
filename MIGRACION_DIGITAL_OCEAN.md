# Guía de Migración a Digital Ocean - Sistema Biblioteca

## Problemas Identificados y Solucionados

### 1. Rutas de Imágenes Inconsistentes
**Problema**: Las rutas de las portadas de libros no se mostraban correctamente después de la migración.

**Solución Aplicada**:
- ✅ Unificado el almacenamiento de imágenes en `public/img/portadas/`
- ✅ Corregido el `LibroController` para usar rutas consistentes
- ✅ Creado helper `imagen_libro()` para manejo centralizado de rutas
- ✅ Actualizado todas las vistas para usar el nuevo helper

### 2. Configuración de Digital Ocean
**Archivos Creados**:
- ✅ `.env.digitalocean` - Variables de entorno para producción
- ✅ `digitalocean-setup.sh` - Script de configuración automática
- ✅ `public/.htaccess.digitalocean` - Configuración optimizada del servidor web

### 3. Helper de Imágenes
**Creado**: `app/Helpers/ImageHelper.php` con funciones:
- `imagen_libro($portada)` - Genera URL correcta para portadas
- `es_produccion()` - Detecta entorno de producción
- `url_completa($ruta)` - Genera URLs completas

## Instrucciones para Deploy en Digital Ocean

### Paso 1: Preparar el Servidor
```bash
# 1. Subir archivos al servidor (excepto vendor/, node_modules/, .env)
# 2. En el servidor, ejecutar:
cd /var/www/tu-proyecto
composer install --optimize-autoloader --no-dev
```

### Paso 2: Configurar Variables de Entorno
En el panel de Digital Ocean, configurar las variables del archivo `.env.digitalocean`:
```
APP_NAME=Sistema Biblioteca
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=biblioteca_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### Paso 3: Ejecutar Script de Configuración
```bash
chmod +x digitalocean-setup.sh
./digitalocean-setup.sh
```

### Paso 4: Configurar Servidor Web
- DocumentRoot: `/var/www/tu-proyecto/public`
- Copiar contenido de `public/.htaccess.digitalocean` a `public/.htaccess`

### Paso 5: Permisos
```bash
sudo chown -R www-data:www-data /var/www/tu-proyecto
sudo chmod -R 755 /var/www/tu-proyecto/storage
sudo chmod -R 755 /var/www/tu-proyecto/bootstrap/cache
sudo chmod -R 755 /var/www/tu-proyecto/public/img
```

## Características Corregidas

### ✅ Gestión de Imágenes
- Portadas de libros se almacenan en `public/img/portadas/`
- Helper centralizado para URLs de imágenes
- Compatibilidad con diferentes formatos de ruta

### ✅ Rendimiento
- Cache de configuración activado
- Optimización de autoload
- Compresión gzip habilitada
- Headers de cache para recursos estáticos

### ✅ Seguridad
- Headers de seguridad añadidos
- Protección de archivos sensibles
- Configuración para HTTPS

### ✅ Estados de Préstamos
- Corregido filtro 'devuelto' en historial
- Estados consistentes en todas las vistas

## Archivos Modificados

### Controladores
- `app/Http/Controllers/LibroController.php` - Rutas de imágenes unificadas

### Vistas
- `resources/views/historial.blade.php` - Uso del helper imagen_libro()
- `resources/views/catalogo.blade.php` - Rutas corregidas y helper implementado
- `resources/views/administrador.blade.php` - Helper imagen_libro()
- `resources/views/libros/edit.blade.php` - Helper imagen_libro()

### Configuración
- `composer.json` - Autoload del helper añadido
- `app/Http/Controllers/UsuarioController.php` - Filtro 'devuelto' corregido

## Testing Post-Migración

### Verificar estas funcionalidades:
1. ✅ Subida de nuevas portadas de libros
2. ✅ Edición de libros existentes
3. ✅ Visualización de portadas en catálogo
4. ✅ Historial de préstamos con filtro 'devuelto'
5. ✅ Panel de administrador con imágenes
6. ✅ Rendimiento de carga de páginas

## Comandos de Mantenimiento

### Limpiar Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Optimizar para Producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Regenerar Enlaces de Storage
```bash
php artisan storage:link
```

## Notas Importantes

1. **Backup**: Siempre hacer backup de la base de datos antes de aplicar cambios
2. **Testing**: Probar todas las funcionalidades después del deploy
3. **Logs**: Revisar logs de errores en `storage/logs/`
4. **Performance**: Monitorear rendimiento y optimizar según necesidad

---
**Actualizado**: 24 de Junio, 2025
**Estado**: Migración completada y probada
