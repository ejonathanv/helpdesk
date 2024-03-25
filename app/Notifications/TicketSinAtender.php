<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketSinAtender extends Notification
{
    use Queueable;

    public $tickets;

    /**
     * Create a new notification instance.
     */
    public function __construct(object $tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Tickets sin atender')
                    ->line('Tienes (' . $this->tickets->count() . ') tickets sin atender.')
                    ->line('Por favor atiende los tickets que no tienen severidad, categoría o prioridad asignadas.')
                    ->action('Ver tickets', route('tickets.index') . '?withoutAttended=true')
                    ->salutation('Gracias.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'tickets_unattended',
            'subject' => '(' . $this->tickets->count() . ') Tickets sin atender',
            'message' => 'Por favor atiende los tickets que no tienen severidad, categoría o prioridad asignadas.',
            'route' => route('tickets.index') . '?withoutAttended=true',
            'date' => Carbon::now()->format('d M, Y H:i a'),
        ];
    }
}
