<?php

namespace App\Providers;

use App\Providers\AsignacionDeIngeniero;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TicketEnProceso
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
    public function handle(AsignacionDeIngeniero $event): void
    {
        $ticket = $event->ticket;

        $ticket->status_id = 3;

        $ticket->save();
    }
}
