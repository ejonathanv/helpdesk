<?php
namespace App\Providers;
use App\Providers\TicketArchivado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
class EstadoDelTicketArchivado
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
    public function handle(TicketArchivado $event): void
    {
        $ticket = $event->ticket;
        $ticket->histories()->create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => 'Se archivó el ticket.'
        ]);
        $ticket->delete();
    }
}
