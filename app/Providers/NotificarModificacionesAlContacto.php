<?php

namespace App\Providers;

use App\Mail\TicketStatusUpdated;
use App\Providers\TicketModificado;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificarModificacionesAlContacto
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
    public function handle(TicketModificado $event): void
    {
        $ticket = $event->ticket;
        $email_del_contacto = $ticket->contact_email;

        Mail::to($email_del_contacto)->send(new TicketStatusUpdated($ticket));
    }
}
