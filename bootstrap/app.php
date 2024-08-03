<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: static function(): void
        {
            Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
        },
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(static function (Middleware $middleware): void
    {
        //
    })
    ->withExceptions(static function (Exceptions $exceptions): void
    {
        //
    })->create();
