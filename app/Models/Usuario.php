<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'dni',
        'codigo_estudiante',
        'fecha_registro',
        'password'
    ];
    protected $hidden = [
        'password'
    ];
    public $timestamps = false;

    // Relaciones
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_usuario');
    }

    public function getRouteKeyName()
    {
        return 'id_usuario';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}


