<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewContactChatMessage extends Notification
{
    use Queueable;

    public $ticket;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket, $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
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
                    ->subject('Mensaje nuevo en Ticket #' . $this->ticket->number)
                    ->line('Has recibido un nuevo mensaje en el ticket #' . $this->ticket->number)
                    ->action('Ver ticket', route('tickets.show', $this->ticket->id))
                    ->line('Saludos,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'chat_message',
            'subject' => 'Mensaje nuevo en Ticket #' . $this->ticket->number,
            'message' => Str::limit($this->message->message, 50),
            'route' => route('tickets.show', $this->ticket->id) . '#chat',
            'date' => Carbon::now()->format('d M, Y H:i a'),
        ];
    }
}
