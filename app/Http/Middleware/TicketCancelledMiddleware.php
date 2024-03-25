<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketCancelledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = auth()->user();
        $type = $user ? 'agent' : 'guest';

        $ticket = $request->route('ticket');
        $status_id = $ticket->status_id;

        if($status_id === 7){
            if($type === 'agent'){
                return redirect()->route('tickets.index')->with('ticketCancelled', 'El ticket ha sido cancelado.');
            }else{
                return redirect()->route('guest.tickets')->with('ticketCancelled', 'El ticket ha sido cancelado.');
            }
        }

        return $next($request);
    }
}
