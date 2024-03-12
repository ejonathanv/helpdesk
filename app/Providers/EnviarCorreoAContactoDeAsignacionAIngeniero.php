<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use App\Providers\AsignacionDeIngeniero;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarCorreoAContactoDeAsignacionAIngeniero
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
        $contact_email = $ticket->contact_email;

        // Vamos a enviar un email al contacto para notificarle que se le asignó un ticket

        if($contact_email){
            Mail::to($contact_email)
                ->send(new \App\Mail\NotifyContactOfEngineerAssignment($ticket));
        }
    }
}
