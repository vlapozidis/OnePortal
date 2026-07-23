<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPasswordChangeRequired
{
    /**
     * Routes a user with a pending forced password change must still be able to reach.
     *
     * @var array<int, string>
     */
    private const ALLOWED_ROUTES = [
        'password.force-change',
        'password.force-change.update',
        'logout',
    ];

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password && ! $request->routeIs(...self::ALLOWED_ROUTES)) {
            return redirect()->route('password.force-change');
        }

        return $next($request);
    }
}
