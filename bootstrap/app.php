<?php

// bootstrap/app.php

use Illuminate\Foundation\Application;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\PpdbActiveMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'auth' => Authenticate::class,
        'guest' => RedirectIfAuthenticated::class, // Ini yang akan dipanggil oleh guest:admin dan guest:web
        'role' => RoleMiddleware::class,
        'ppdb.active' => PpdbActiveMiddleware::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withCommands([
    // Daftarkan command manual jika perlu
    \App\Console\Commands\CalculateStudentDistances::class,
])
    ->create();
