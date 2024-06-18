<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CorsMiddleware::class,
        // Altri middleware globali qui...
    ];

    // Altri middleware di gruppo...

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\VerifyCsrfToken::class,  // Aggiungi questa linea
            // Altri middleware per le rotte web...
        ],

        'api' => [
            // Middleware per le rotte API...
        ],
    ];

    // Altri metodi e proprietà...
}
