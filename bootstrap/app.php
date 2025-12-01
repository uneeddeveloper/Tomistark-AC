<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// (opsional, biar IDE auto-complete enak)
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TechnicianMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /**
         * Route middleware alias
         * Catatan:
         * - Alias bawaan Laravel (auth, guest, verified, dsb) tetap ada.
         * - Di sini kita TAMBAHKAN alias khusus aplikasi.
         */
        $middleware->alias([
            'role'       => CheckRole::class,        // dukung multi-role: role:admin,technician
            'admin'      => AdminMiddleware::class,  // wrapper khusus admin
            'technician' => TechnicianMiddleware::class, // wrapper khusus teknisi
        ]);

        /**
         * Web middleware group
         * (biarkan ringan; auth/role dipasang per-route/group di routes/web.php)
         */
        $middleware->web([
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Jika kamu punya API routes, bisa aktifkan group berikut juga:
        // $middleware->api([
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
