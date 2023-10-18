<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInvitation extends Notification
{
    use Queueable;

    public $invitacion;

    /**
     * Create a new notification instance.
     */
    public function __construct($invitacion)
    {
        $this->invitacion = $invitacion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([]);
    }

    public function toDatabase($notifiable)
    {
        // Agrega la información necesaria en el arreglo que se almacenará en la base de datos.
        return [
            'message' => 'Has recibido una nueva invitación.',
            'invitation_id' => $this->invitacion->id, // Puedes pasar datos adicionales
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
