<?php

namespace App\Providers;

use App\Mail\WelcomeMail;
use App\Providers\UsuarioCreado;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosDeAccesoAlNuevoUsuario
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
    public function handle(UsuarioCreado $event): void
    {
        $user = $event->user;
        $password = $event->password;

        Mail::to($user->email)->send(new WelcomeMail($user->name, $user->email, $password));
    }
}
