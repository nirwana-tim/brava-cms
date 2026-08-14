<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts;

class TrustAppHosts extends TrustHosts
{
    /**
     * @return list<string>
     */
    public function hosts(): array
    {
        $hosts = ['^localhost$', '^127\.0\.0\.1$', '^[\w.-]+\.test$'];

        foreach ([config('app.url'), config('frontend_url')] as $url) {
            $host = (string) parse_url((string) $url, PHP_URL_HOST);

            if ($host === '') {
                continue;
            }

            $escaped = preg_quote($host, '/');

            $hosts[] = '^'.$escaped.'$';
            $hosts[] = '^[\w.-]+\.'.$escaped.'$';
        }

        return array_values(array_unique($hosts));
    }
}
