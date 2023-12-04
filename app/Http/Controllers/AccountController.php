<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class AccountController extends Controller
{
    public function list()
    {
        $source = env('CRM_URL');
        $token = env('CRM_TOKEN');

        $client = new Client();

        try {
            $response = $client->get($source . '/Account', [
                'headers' => [
                    'X-Api-Key' => $token,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $accounts = json_decode($response->getBody()->getContents());
                // We need only the ID and NAME of the accounts
                $accounts = collect($accounts->list)->map(function ($account) {
                    return [
                        'id' => $account->id,
                        'name' => $account->name,
                    ];
                });
                return $accounts->sortBy('name')->values()->all();
            } else {
                // Manejar posibles errores de respuesta aquí
                return [];
            }
        } catch (Exception $e) {
            // Manejar excepciones aquí
            return [];
        }
    }

    public function account($account_id){

        $source = env('CRM_URL') . "/Account/" . $account_id;
        $token = env('CRM_TOKEN');

        $client = new Client();

        try {
            $response = $client->get($source, [
                'headers' => [
                    'X-Api-Key' => $token,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $account = json_decode($response->getBody()->getContents());
                // Aquí necesitamos el ID, nombre y correo del gerente de la cuenta
                $manager = $this->user($account->assignedUserId);

                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'manager_id' => $manager ? $manager['id'] : null,
                    'manager_name' => $manager ? $manager['name'] : null,
                    'manager_email' => $manager ? $manager['email'] : null,
                ];
            } else {
                // Manejar posibles errores de respuesta aquí
                return [];
            }
        } catch (Exception $e) {
            // Manejar excepciones aquí
            return [];
        }

    }

    public function user($userId){
        $source = env('CRM_URL') . "/User/" . $userId;
        $token = env('CRM_TOKEN');

        $client = new Client();

        try {
            $response = $client->get($source, [
                'headers' => [
                    'X-Api-Key' => $token,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $user = json_decode($response->getBody()->getContents());
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->emailAddress,
                ];
            } else {
                // Manejar posibles errores de respuesta aquí
                return "No se pudo obtener el usuario";
            }
        } catch (RequestException $e) {
            // obteniendo la excepción 404
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                return [
                    'id' => null,
                    'name' => null,
                    'email' => null,
                ];
            }

            return [];
        } catch (Exception $e) {
            // Otras excepciones
            return [];
        }
    }

    public function contacts($account_id){
        $source = env('CRM_URL') . "/Account/" . $account_id . "/contacts";
        $token = env('CRM_TOKEN');

        $client = new Client();

        try {
            $response = $client->get($source, [
                'headers' => [
                    'X-Api-Key' => $token,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $contacts = json_decode($response->getBody()->getContents());
                // Aquí necesitamos el ID, nombre y correo del gerente de la cuenta
                $contacts = collect($contacts->list)->map(function ($contact) {
                    return [
                        'id' => $contact->id,
                        'name' => $contact->name,
                        'email' => $contact->emailAddress,
                        'job_title' => $contact->jobTitle,
                    ];
                });
                return $contacts->sortBy('name')->values()->all();
            } else {
                // Manejar posibles errores de respuesta aquí
                return [];
            }
        } catch (Exception $e) {
            // Manejar excepciones aquí
            return [];
        }
    }

    public function contact($contact_id){
        $source = env('CRM_URL') . "/Contact/" . $contact_id;
        $token = env('CRM_TOKEN');

        $client = new Client();

        try {
            $response = $client->get($source, [
                'headers' => [
                    'X-Api-Key' => $token,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $contact = json_decode($response->getBody()->getContents());
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->emailAddress,
                ];
            } else {
                // Manejar posibles errores de respuesta aquí
                return [];
            }
        } catch (Exception $e) {
            // Manejar excepciones aquí
            return [];
        }
    }
}
