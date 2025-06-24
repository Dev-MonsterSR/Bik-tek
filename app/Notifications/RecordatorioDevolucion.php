<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RecordatorioDevolucion extends Notification
{
    use Queueable;

    protected $prestamo;

    public function __construct($prestamo)
    {
        $this->prestamo = $prestamo;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recordatorio de Devolución - Sistema Biblioteca')
            ->line('Le recordamos que tiene un libro próximo a vencer:')
            ->line('Libro: ' . $this->prestamo->libro->titulo)
            ->line('Fecha de devolución estimada: ' . $this->prestamo->fecha_devolucion_estimada->format('d/m/Y'))
            ->line('Por favor, devuelva el libro a tiempo para evitar sanciones.')
            ->action('Ver Detalles del Préstamo', url('/prestamos/' . $this->prestamo->id_prestamo));
    }

    public function toArray($notifiable)
    {
        return [
            'prestamo_id' => $this->prestamo->id_prestamo,
            'libro_titulo' => $this->prestamo->libro->titulo,
            'fecha_devolucion' => $this->prestamo->fecha_devolucion_estimada
        ];
    }
}