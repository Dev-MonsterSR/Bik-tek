<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $table = 'trabajadores';
    protected $primaryKey = 'id_trabajador';
    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'nombre',
        'apellido',
        'email',
        'dni',
        'telefono',
        'direccion',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}
