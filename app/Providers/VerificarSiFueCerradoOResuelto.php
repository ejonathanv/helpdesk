<?php

namespace App\Providers;

use App\Providers\TicketModificado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerificarSiFueCerradoOResuelto
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

        if($ticket->status_id == 2){
            $ticket->solved_date = now();
            $ticket->save();
        }

        if($ticket->status_id == 6){
            $ticket->closed_date = now();
            $ticket->save();
        }
    }
}
