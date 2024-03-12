<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Providers\DesarchivarTicket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EstadoDelTicketDesarchivado
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
    public function handle(DesarchivarTicket $event): void
    {
        $ticketId = $event->ticket;
        
        $ticket = Ticket::withTrashed()->find($ticketId);

        $ticket->restore();

        $ticket->histories()->create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => 'Se desarchivó el ticket.'
        ]);
    }
}
