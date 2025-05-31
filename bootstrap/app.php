<?php

use App\Http\Middleware\Translations;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->remove(TrimStrings::class);
        $middleware->remove(ConvertEmptyStringsToNull::class);
        $middleware->group('web', [
            // Asegúrate de poner esto primero
            \Illuminate\Session\Middleware\StartSession::class,
    
            // Luego tu middleware
            \App\Http\Middleware\Translations::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
