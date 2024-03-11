<?php

namespace App\Providers;

use App\Notifications\TicketAssigned;
use App\Providers\AsignacionDeIngeniero;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarCorreoDeAsignacionAIngeniero
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
        $ingeniero = $event->ingeniero;

        $ticket->histories()->create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => 'Se asignó a ' . $ingeniero->user->name . ' como ingeniero responsable.'
        ]);

        $ingeniero->user->notify(new TicketAssigned($ticket));
    }
}
