<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCancelado extends Notification
{
    use Queueable;


    public $ticket;
    public $reason;


    /**
     * Create a new notification instance.
     */
    public function __construct($ticket, $reason)
    {
        $this->ticket = $ticket;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
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
            'type' => 'ticket_cancelled',
            'subject' => 'Ticket #' . $this->ticket->number . ' ha sido cancelado por el contacto',
            'message' => 'Razón: ' . $this->reason,
            'route' => route('tickets.show', $this->ticket->id),
            'date' => Carbon::now()->format('d M, Y H:i a'),
        ];
    }
}
