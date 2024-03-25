<?php

namespace App\Providers;

use App\Mail\SendCredentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Providers\EnviarCredencialesAlContacto;

class EnviarCredencialesPorCorreoAlContacto
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
    public function handle(EnviarCredencialesAlContacto $event): void
    {
        $contact = $event->contact;
        $email = $contact['email'];
        $password = $contact['password'];

        if($password){
            // Send an email to the contact with the credentials
            Mail::to($email)->send(new SendCredentials($contact));
        }else{
            event(new CredencialesDeContacto($contact));
        }

    }
}
