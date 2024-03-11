<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use Illuminate\Http\Request;

class GuestLoginController extends Controller
{
    public function showLoginForm(){

        // Debemos comprobar si hay un usuario autenticado

        $authUser = auth()->user();

        if($authUser){
            // Si hay un usuario autenticado cerramos la sesión
            auth()->logout();
        }

        return Inertia::render('Guest/Login');
    }

    public function login(Request $request)
    {

        // Validar información del formulario

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El campo email debe ser un email válido',
            'password.required' => 'El campo password es obligatorio',
        ]);

        // Aquí vamos a intentar iniciar sesión con los datos que nos envía el usuario
        // Si el usuario existe, lo redirigimos a la vista de tickets
        // Si el usuario no existe, lo redirigimos a la vista de login con un mensaje de error

        $email = $request->email;
        $password = $request->password;

        $source = env('CRM_URL') . "/Contact?select=id,name,emailAddress,accountsIds&maxSize=20&offset=0&orderBy=createdAt&order=desc&where[0][type]=contains&where[0][attribute]=emailAddress&where[0][value]=" . $email . "&where[1][type]=contains&where[1][attribute]=codigocontacto&where[1][value]=" . $password;

        $token = env('CRM_TOKEN');

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->get($source, [
                'headers' => [
                    'X-Api-Key' => $token,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $contact = json_decode($response->getBody()->getContents());
                $contact = collect($contact->list)->first();

                if(!$contact){
                    return redirect()->back()->with('error', 'Las credenciales no son válidas');
                }

                // Aquí se va a guardar el contact_id en la sesión

                session([
                    'type' => 'guest',
                    'contact_id' => $contact->id,
                    'contact_name' => $contact->name,
                    'contact_email' => $contact->emailAddress,
                    'accounts' => $contact->accountsNames,
                ]);

                return redirect()->route('guest.dashboard');
            } else {
                // Manejar posibles errores de respuesta aquí
                return redirect()->back()->with('error', 'Las credenciales no son válidas');
            }
        } catch (Exception $e) {
            // Manejar excepciones aquí
            return redirect()->back()->with('error', 'Las credenciales no son válidas');
        }
    }

    public function logout()
    {
        // Aquí vamos a cerrar la sesión
        session()->flush();
        return redirect()->route('guest.login');
    }
}
