<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

test('it adds hardening headers to every response', function () {
    $middleware = new SecurityHeaders;
    $request = Request::create('/', 'GET');

    $response = $middleware->handle($request, fn () => new Response('ok'));

    expect($response->headers->get('X-Frame-Options'))->toBe('DENY');
    expect($response->headers->get('X-Content-Type-Options'))->toBe('nosniff');
    expect($response->headers->get('Referrer-Policy'))->toBe('strict-origin-when-cross-origin');
    expect($response->headers->has('X-Powered-By'))->toBeFalse();
});

test('it only sends hsts over a secure connection', function () {
    $middleware = new SecurityHeaders;

    $insecure = Request::create('http://example.com');
    $response = $middleware->handle($insecure, fn () => new Response('ok'));
    expect($response->headers->has('Strict-Transport-Security'))->toBeFalse();

    $secure = Request::create('https://example.com');
    $response = $middleware->handle($secure, fn () => new Response('ok'));
    expect($response->headers->get('Strict-Transport-Security'))->toContain('max-age=31536000');
});
