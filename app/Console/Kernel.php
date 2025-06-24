<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\VerificarDevolucionesTardias::class,
        Commands\EnviarRecordatoriosDevolucion::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Verificar devoluciones tardías diariamente a las 00:00
        $schedule->command('prestamos:verificar-devoluciones')->daily();

        // Enviar recordatorios diariamente a las 09:00
        $schedule->command('prestamos:recordatorios')->dailyAt('09:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
