<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheApiHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && $request->is('api/*') && $response->isSuccessful()) {
            $response->headers->set('Cache-Control', 'public, max-age=900, s-maxage=900');
        }

        return $response;
    }
}
