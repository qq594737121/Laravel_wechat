<?php namespace App\Http\Middleware;

use Closure;
use Response;

class EnableCrossRequestMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Origin, Cookie, Accept, Authorization, X-Requested-With, Application');

        //$response->header('Access-Control-Allow-Origin', '*');
        //$response->header('Access-Control-Allow-Headers', 'content-type, Origin, Cookie, Accept, X-Requested-With, X_Requested_With');
        //$response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
        //$response->header('Access-Control-Allow-Credentials', 'true');

        return $response;
    }

}