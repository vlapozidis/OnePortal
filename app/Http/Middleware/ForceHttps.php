<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            ! $request->secure()
            && ! app()->runningUnitTests()
            && ! in_array($request->getHost(), ['localhost', '127.0.0.1'], true)
        ) {
            return redirect()->to('https://'.$request->getHttpHost().$request->getRequestUri());
        }

        return $next($request);
    }
}
