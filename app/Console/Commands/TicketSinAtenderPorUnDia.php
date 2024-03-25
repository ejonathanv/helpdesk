<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Permission;
use Illuminate\Console\Command;
use App\Notifications\TicketSinAtender;

class TicketSinAtenderPorUnDia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:recordatorio-de-atencion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recordar a los colaboradores que tienen tickets sin atender por un día';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        // Vamos a buscar todos los tickets que no tengan una severidad, ni categoria, ni prioridad asignadas.
        $tickets = Ticket::where('severity_id', '')
            ->orWhere('category_id', '')
            ->orWhere('priority_id', '')
            ->get()
            ->filter(function($ticket){
                $status = $ticket->status_id;
                return $status != 6 && $status != 7;
            });


        print("Tickets sin atender: " . $tickets->count() . "\n");

        return false;

        // Vamos a buscar todos los colaboradores que tengan el permiso de colaborador.
        $permisoDeColaborador = Permission::where('name', 'Colaborador')->first();
        $colaboradores = $permisoDeColaborador->users;


        // Le vamos a enviar una notificacion a cada colaborador diciendole la cantidad de tickets que hay sin atender.
        foreach ($colaboradores as $colaborador) {
            $usuario = $colaborador->user;

            $usuario->notify(new TicketSinAtender($tickets));
        }
        

    }
}
