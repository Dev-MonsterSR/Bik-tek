<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamo';
    protected $primaryKey = 'id_prestamo';
    protected $fillable = [
        'id_usuario', 'id_libro', 'fecha_prestamo', 'fecha_devolucion', 'estado', 'observaciones'
    ];
    public $timestamps = false;


    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro', 'id_libro');
    }

    public function devoluciones()
    {
        return $this->hasMany(\App\Models\Devolucion::class, 'id_prestamo', 'id_prestamo');
    }
}
