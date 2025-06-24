<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sancion extends Model
{
    protected $table = 'sanciones';
    protected $primaryKey = 'id_sancion';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'dias_bloqueo',
        'fecha_inicio',
        'fecha_fin',
        'tipo',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'dias_bloqueo' => 'integer'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Scope para sanciones activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa')
                    ->where('fecha_fin', '>', now());
    }

    // Método para verificar si la sanción está activa
    public function estaActiva()
    {
        return $this->estado === 'activa' && $this->fecha_fin > now();
    }

    // Método para completar una sanción
    public function completar()
    {
        $this->estado = 'completada';
        $this->save();
    }

    // Método para cancelar una sanción
    public function cancelar()
    {
        $this->estado = 'cancelada';
        $this->save();
    }
}
