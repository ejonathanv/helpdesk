<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'ticketDetails' => fn () => $request->session()->get('ticketDetails'),
                'ticketContact' => fn () => $request->session()->get('ticketContact'),
                'ticketAgent' => fn () => $request->session()->get('ticketAgent'),
                'newEventCreated' => fn () => $request->session()->get('newEventCreated'),
                'eventDeleted' => fn () => $request->session()->get('eventDeleted'),
                'ticketArchived' => fn () => $request->session()->get('ticketArchived'),
                'agentUpdated' => fn () => $request->session()->get('agentUpdated'),
                'agentPermissionsUpdated' => fn () => $request->session()->get('agentPermissionsUpdated'),
                'agentSecurityUpdated' => fn () => $request->session()->get('agentSecurityUpdated'),
                'agentSuspended' => fn () => $request->session()->get('agentSuspended'),
                'agentDeleted' => fn () => $request->session()->get('agentDeleted'),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
