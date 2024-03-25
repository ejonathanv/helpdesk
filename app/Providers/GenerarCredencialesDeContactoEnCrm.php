<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Queue\InteractsWithQueue;
use App\Providers\CredencialesDeContacto;
use App\Http\Controllers\AccountController;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerarCredencialesDeContactoEnCrm
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
    public function handle(CredencialesDeContacto $event): void
    {
        $contact = $event->contact;

        $password = Str::password(8);

        $statusCode = app(AccountController::class)->generate_password($contact, $password);

        if($statusCode == 200){
            $contactWithNewPassword = app(AccountController::class)->get_password($contact['id']);
            event(new EnviarCredencialesAlContacto($contactWithNewPassword));
        }else{
            info('No se pudieron generar las credenciales');
        }
    }
}
