<?php

use App\Http\Middleware\CheckEntraIDConfiguration;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'check-entra-config' => CheckEntraIDConfiguration::class,
        ]);

        // Vercel (and most PaaS hosts) terminate TLS at the edge and forward
        // requests over plain HTTP with X-Forwarded-* headers. Without
        // trusting that proxy, $request->secure() always reports false and
        // ForceHttps redirect-loops the app.
        $middleware->trustProxies(at: '*');

        $middleware->web(prepend: [
            ForceHttps::class,
        ]);

        $middleware->web(append: [
            SecurityHeaders::class,
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

// Vercel's function filesystem is read-only except /tmp. Redirect Laravel's
// framework cache/session/view storage there so it can write on cold starts.
if (getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');

    foreach ([
        'framework/cache/data',
        'framework/sessions',
        'framework/testing',
        'framework/views',
        'app/public',
        'logs',
    ] as $dir) {
        $path = '/tmp/storage/'.$dir;

        if (! is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }
}

return $app;
