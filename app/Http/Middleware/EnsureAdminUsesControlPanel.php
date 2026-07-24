<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUsesControlPanel
{
    /**
     * Routes an admin may still reach outside of the control panel.
     *
     * @var array<int, string>
     */
    private const ALLOWED_ROUTE_NAMES = [
        'logout',
    ];

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->isAdmin()
            && ! $request->is('control-panel*')
            && ! $request->is('livewire*')
            && ! $request->routeIs(...self::ALLOWED_ROUTE_NAMES)
        ) {
            return redirect('/control-panel');
        }

        return $next($request);
    }
}
