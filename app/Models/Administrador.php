<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administrador';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['usuario', 'clave', 'nombre', 'correo'];
    protected $hidden = ['clave'];
    public $timestamps = true;
}
