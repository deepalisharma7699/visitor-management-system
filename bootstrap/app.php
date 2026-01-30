<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.visitor' => \App\Http\Middleware\VisitorAuth::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        
        // Use custom Authenticate middleware for handling redirects
        $middleware->redirectGuestsTo(fn ($request) => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
