<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// $app = new Illuminate\Foundation\Application(
//     $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
// );


// $app->singleton(
//     Illuminate\Contracts\Http\Kernel::class,
//     App\Http\Kernel::class
// );

// $app->singleton(
//     Illuminate\Contracts\Console\Kernel::class,
//     App\Console\Kernel::class
// );

// $app->singleton(
//     Illuminate\Contracts\Debug\ExceptionHandler::class,
//     App\Exceptions\Handler::class
// );

// return $app;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
