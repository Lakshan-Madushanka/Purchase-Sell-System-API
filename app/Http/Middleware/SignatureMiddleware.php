<?php

namespace App\Http\Middleware;

use Closure;
use League\Flysystem\Config;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $headerName = 'X-Name')
    {
        $response =  $next($request);

        $response->headers->set($headerName, Config('app.name'));

        return $response;
    }
}
