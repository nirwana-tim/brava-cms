<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromQuery
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->query('lang');

        if (is_string($lang) && in_array($lang, ['id', 'en'], true)) {
            App::setLocale($lang);
        } else {
            App::setLocale('id');
        }

        return $next($request);
    }
}
