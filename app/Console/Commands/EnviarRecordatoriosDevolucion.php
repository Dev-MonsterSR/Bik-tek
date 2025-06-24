<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prestamo;
use App\Notifications\RecordatorioDevolucion;
use Carbon\Carbon;

class EnviarRecordatoriosDevolucion extends Command
{
    protected $signature = 'prestamos:recordatorios';
    protected $description = 'Envía recordatorios de devolución a usuarios con préstamos próximos a vencer';

    public function handle()
    {
        $prestamos = Prestamo::with(['usuario', 'libro'])
            ->where('estado', 'activo')
            ->where('fecha_devolucion_estimada', '>', Carbon::now())
            ->where('fecha_devolucion_estimada', '<=', Carbon::now()->addDays(2))
            ->get();

        foreach ($prestamos as $prestamo) {
            $prestamo->usuario->notify(new RecordatorioDevolucion($prestamo));
            $this->info("Recordatorio enviado a {$prestamo->usuario->nombre} para el libro {$prestamo->libro->titulo}");
        }

        $this->info('Proceso de recordatorios completado.');
    }
}
