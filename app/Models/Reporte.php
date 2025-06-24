<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $primaryKey = 'id_reporte';
    protected $fillable = ['tipo', 'fecha_generacion'];
}
