<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentTicketOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = auth()->user();
        $agent = $user->agent;
        $permission = $user->permission->name;
        $ticket = $request->route('ticket');

        if($permission === 'Ingeniero' && $agent->id !== $ticket->agent_id){
            abort(403);
        }

        if($permission == 'Supervisor'){
            // Vamos a revisar si el departamento del ticket es el mismo que el del supervisor o si el ticket es de un agente que pertenece a su departamento o bien si el ticket lo creo el mismo supervisor

            $ticketDepartment = false;
            $ticketAgent = false;
            $ticketUser = false;

            if($ticket->department_id === $agent->department_id){
                $ticketDepartment = true;
            }

            if($ticket->agent_id === $agent->id){
                $ticketAgent = true;
            }

            if($ticket->user_id === $user->id){
                $ticketUser = true;
            }

            if(!$ticketDepartment && !$ticketAgent && !$ticketUser){
                abort(403);
            }


        }

        return $next($request);
    }
}
