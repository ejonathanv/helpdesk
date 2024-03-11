<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use App\Providers\AsignacionDeContacto;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarCorreoDeAsignacion
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
    public function handle(AsignacionDeContacto $event): void
    {
        $ticket = $event->ticket;
        $contacto = $event->contacto;

        // Se crea una historia para el ticket describiendo que se asignó un contacto a la cuenta
        $ticket->histories()->create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => 'Se asignó a ' . $contacto['name'] . ' como contacto de la cuenta.'
        ]);

        // Vamos a enviar un email al contacto para notificarle que se le asignó un ticket

        if($contacto['email']){
            Mail::to($contacto['email'])->send(new \App\Mail\ContactTicketAssigned($ticket));
        }

    }
}
