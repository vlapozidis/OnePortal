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

return Application::configure(basePath: dirname(__DIR__))
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

        // Azure App Service terminates TLS at its edge and forwards requests
        // over plain HTTP with X-Forwarded-* headers. Without trusting that
        // proxy, $request->secure() always reports false and ForceHttps
        // redirect-loops the app.
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
