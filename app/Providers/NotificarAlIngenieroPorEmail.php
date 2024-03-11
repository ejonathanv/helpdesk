<?php

namespace App\Providers;

use Illuminate\Queue\InteractsWithQueue;
use App\Providers\MensajeEnChatPorContacto;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NewContactChatMessage;

class NotificarAlIngenieroPorEmail
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
    public function handle(MensajeEnChatPorContacto $event): void
    {

        $ticket = $event->ticket;
        $message = $event->message;

        $ticket->agent->user->notify(new NewContactChatMessage($ticket, $message));
    }
}
