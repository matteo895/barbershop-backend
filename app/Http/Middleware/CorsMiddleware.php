<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Esegue la logica del middleware per gestire le richieste CORS
        $response = $next($request);
        // Passa la richiesta al prossimo middleware nella pipeline

        // Aggiunge l'header Access-Control-Allow-Origin per consentire l'accesso da tutte le origini
        $response->headers->set('Access-Control-Allow-Origin', '*'); // Può essere specificato un dominio specifico al posto di '*'

        // Aggiunge l'header Access-Control-Allow-Methods per specificare i metodi HTTP consentiti
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

        // Aggiunge l'header Access-Control-Allow-Headers per specificare gli header HTTP consentiti
        $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization, X-CSRF-TOKEN');

        return $response;
    }
}
