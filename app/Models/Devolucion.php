<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones'; // <--- Agrega esta línea
    protected $primaryKey = 'id_devolucion';
    protected $fillable = ['id_prestamo', 'fecha_devolucion', 'estado_libro'];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }
}
