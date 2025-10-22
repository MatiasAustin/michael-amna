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
    ->withMiddleware(function (Middleware $middleware): void {

        // Tambahkan session middleware agar Session::get() bisa dipakai
        // Ensure cookies & sessions work like the default "web" group:

        $middleware->append(\Illuminate\Session\Middleware\StartSession::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Kosongkan atau tambahkan konfigurasi valid
    })
    ->create();
