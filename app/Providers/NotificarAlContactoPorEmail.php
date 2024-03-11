<?php

namespace App\Providers;

use App\Mail\NewChatMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Providers\MensajeEnChatPorIngeniero;

class NotificarAlContactoPorEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MensajeEnChatPorIngeniero $event): void
    {

        $ticket = $event->ticket;
        $message = $event->message;

        Mail::to($ticket->contact_email)
                ->send(new NewChatMessage($ticket, $message));
                
    }
}
