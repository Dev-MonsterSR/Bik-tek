<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libro';
    protected $primaryKey = 'id_libro';
    protected $fillable = [
    'codigo', 'titulo', 'autor', 'editorial', 'anio_publicacion', 'categoria_id', 'cantidad', 'disponibles', 'estado', 'portada'
    ];
    public $timestamps = false;

    // Relaciones
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_libro', 'id_libro');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }

    // Métodos para verificar si se puede eliminar
    public function puedeSerEliminado()
    {
        return $this->prestamos()->whereIn('estado', ['pendiente', 'activo', 'retraso'])->count() === 0;
    }

    public function tienePrestamosActivos()
    {
        return $this->prestamos()->whereIn('estado', ['pendiente', 'activo', 'retraso'])->exists();
    }
}
