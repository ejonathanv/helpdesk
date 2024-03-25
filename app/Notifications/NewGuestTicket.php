<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewGuestTicket extends Notification
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Se ha creado un nuevo ticket')
                    ->greeting('Hola ' . $notifiable->name)
                    ->line('Se ha creado un nuevo ticket con el número #' . $this->ticket->number)
                    ->action('Ver ticket', route('tickets.show', $this->ticket->id))
                    ->line('Gracias por usar nuestra aplicación!')
                    ->salutation('Saludos');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_ticket',
            'subject' => 'Se ha creado un nuevo ticket #' . $this->ticket->number,
            'message' => $this->ticket->subject,
            'route' => route('tickets.show', $this->ticket->id),
            'date' => Carbon::now()->format('d M, Y H:i a'),
        ];
    }
}
