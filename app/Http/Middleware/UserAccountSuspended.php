<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccountSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $agent = auth()->user()->agent;

        if($agent){

            $isActive = $agent->status === 'active';

            if(!$isActive){
                auth()->logout();
                return redirect()->route('login')->with('status', 'Tu cuenta ha sido suspendida');         
            }
        }

        return $next($request);
    }
}
