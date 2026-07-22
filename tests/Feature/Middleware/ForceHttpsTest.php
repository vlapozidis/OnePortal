<?php

use App\Http\Middleware\ForceHttps;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

test('it redirects insecure requests on a real host to https', function () {
    // Simulate a non-testing environment so the redirect branch actually runs.
    app()->instance('env', 'local');

    $response = (new ForceHttps)->handle(
        Request::create('http://portalproject.test/dashboard'),
        fn () => new Response('ok')
    );

    expect($response->isRedirect('https://portalproject.test/dashboard'))->toBeTrue();
});

test('it does not redirect requests already on https', function () {
    $response = (new ForceHttps)->handle(
        Request::create('https://portalproject.test/dashboard'),
        fn () => new Response('ok')
    );

    expect($response->getContent())->toBe('ok');
});

test('it does not redirect localhost so local tooling keeps working', function () {
    app()->instance('env', 'local');

    $response = (new ForceHttps)->handle(
        Request::create('http://localhost/dashboard'),
        fn () => new Response('ok')
    );

    expect($response->getContent())->toBe('ok');
});
