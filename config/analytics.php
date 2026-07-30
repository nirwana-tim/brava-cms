<?php

$keyEnv = env('GA4_SERVICE_ACCOUNT_KEY', 'app/analytics/service-account-key.json');
if ($keyEnv && ! (str_starts_with($keyEnv, '/') || str_contains($keyEnv, ':\\'))) {
    $keyEnv = preg_replace('#^/?storage/#', '', $keyEnv);
    $keyPath = storage_path($keyEnv);
} else {
    $keyPath = $keyEnv;
}

return [
    'property_id' => env('GA4_PROPERTY_ID'),

    'service_account_key' => $keyPath,

    'cache_ttl' => [
        'fresh' => (int) env('GA4_CACHE_FRESH', 30),
        'stale' => (int) env('GA4_CACHE_STALE', 60),
    ],
];
