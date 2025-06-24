<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SancionNotificacion extends Notification
{
    use Queueable;

    protected $sancion;

    public function __construct($sancion)
    {
        $this->sancion = $sancion;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mensaje = match($this->sancion->tipo) {
            'retraso' => 'Ha recibido una sanción por devolución tardía.',
            'daño' => 'Ha recibido una sanción por daño al libro.',
            'perdida' => 'Ha recibido una sanción por pérdida del libro.',
            default => 'Ha recibido una sanción.'
        };

        return (new MailMessage)
            ->subject('Notificación de Sanción - Sistema Biblioteca')
            ->line($mensaje)
            ->line('Días de sanción: ' . $this->sancion->dias_bloqueo)
            ->line('Fecha de inicio: ' . $this->sancion->fecha_inicio->format('d/m/Y'))
            ->line('Fecha de fin: ' . $this->sancion->fecha_fin->format('d/m/Y'))
            ->line('No podrá realizar préstamos hasta que la sanción expire.');
    }

    public function toArray($notifiable)
    {
        return [
            'sancion_id' => $this->sancion->id_sancion,
            'tipo' => $this->sancion->tipo,
            'dias_bloqueo' => $this->sancion->dias_bloqueo,
            'fecha_inicio' => $this->sancion->fecha_inicio,
            'fecha_fin' => $this->sancion->fecha_fin
        ];
    }
}
