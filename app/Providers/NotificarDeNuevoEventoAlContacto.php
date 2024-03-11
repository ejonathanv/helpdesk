<?php

namespace App\Providers;

use App\Mail\NewPublicEvent;
use Illuminate\Support\Facades\Mail;
use App\Providers\NuevoEventoPublico;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificarDeNuevoEventoAlContacto
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
    public function handle(NuevoEventoPublico $event): void
    {

        $ticket = $event->ticket;
        $event = $event->event;

        Mail::to($ticket->contact_email)->send(new NewPublicEvent($event));
    }
}
