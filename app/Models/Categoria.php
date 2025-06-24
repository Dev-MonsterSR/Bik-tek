<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';
    protected $fillable = ['nombre', 'descripcion'];
    public $timestamps = false;

    // Relaciones
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_categoria', 'id_categoria');
    }
}
