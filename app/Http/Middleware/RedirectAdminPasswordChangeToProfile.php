<?php

namespace App\Http\Middleware;

use App\Filament\Pages\EditProfile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminPasswordChangeToProfile
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->must_change_password
            && $request->routeIs('filament.admin.auth.logout') === false
            && ! ($request->route()?->getName() === EditProfile::getRouteName())
        ) {
            return redirect(EditProfile::getUrl());
        }

        return $next($request);
    }
}
