<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prestamo;
use App\Models\Sancion;
use App\Models\Libro;
use Carbon\Carbon;

class VerificarDevolucionesTardias extends Command
{
    protected $signature = 'prestamos:verificar-devoluciones';
    protected $description = 'Verifica préstamos vencidos y aplica sanciones automáticamente';

    public function handle()
    {
        $prestamosVencidos = Prestamo::where('estado', 'activo')
            ->where('fecha_devolucion', '<', now())
            ->get();

        foreach ($prestamosVencidos as $prestamo) {
            $diasAtraso = now()->diffInDays($prestamo->fecha_devolucion);

            // Si el libro tiene más de 30 días de atraso, se marca como perdido
            if ($diasAtraso >= 30) {
                $prestamo->update(['estado' => 'perdido']);

                // Actualizar disponibilidad del libro
                $libro = Libro::find($prestamo->id_libro);
                if ($libro) {
                    $libro->disponibles = max(0, $libro->disponibles - 1);
                    $libro->save();
                }

                // Aplicar sanción por libro perdido (30 días)
                Sancion::create([
                    'id_usuario' => $prestamo->id_usuario,
                    'dias_bloqueo' => 30,
                    'fecha_inicio' => now(),
                    'fecha_fin' => now()->addDays(30),
                    'tipo' => 'perdida'
                ]);
            } else {
                // Aplicar sanción por retraso (5 días por cada día de atraso)
                $diasSancion = 5 * $diasAtraso;

                Sancion::create([
                    'id_usuario' => $prestamo->id_usuario,
                    'dias_bloqueo' => $diasSancion,
                    'fecha_inicio' => now(),
                    'fecha_fin' => now()->addDays($diasSancion),
                    'tipo' => 'retraso'
                ]);
            }

            $this->info("Sanción aplicada para el préstamo #{$prestamo->id_prestamo}");
        }

        $this->info('Verificación de devoluciones tardías completada.');
    }
}
