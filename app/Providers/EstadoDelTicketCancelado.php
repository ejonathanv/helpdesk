<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use App\Notifications\TicketCancelado;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Providers\TicketCanceladoPorContacto;

class EstadoDelTicketCancelado
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
    public function handle(TicketCanceladoPorContacto $event): void
    {
        $ticket = $event->ticket;
        $reason = $event->reason;

        $ticket->status_id = 7;
        $ticket->cancellation_reason = $reason;
        $ticket->save();
        $ticket->histories()->create([
            'user_id' => null,
            'ticket_id' => $ticket->id,
            'description' => 'El contacto canceló el ticket.'
        ]);
        
        if($ticket->agent_id) {
            $user = $ticket->agent->user;
            $user->notify(new TicketCancelado($ticket, $reason));
        }
    }
}
