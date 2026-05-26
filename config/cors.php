<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | 'paths'               – which URL patterns CORS headers are applied to.
    |                         'api/*' covers every /api/v1/* endpoint.
    |                         'sanctum/csrf-cookie' is only needed if you ever
    |                         switch to cookie-based Sanctum auth (SPA mode).
    |
    | 'allowed_origins'     – set to ['*'] for local / Expo development so any
    |                         device on the LAN can reach the API.
    |                         In production, restrict to your exact mobile
    |                         bundle origin or app scheme if required.
    |
    | 'supports_credentials'– MUST stay false when allowed_origins is ['*'].
    |                         Browsers forbid wildcard + credentials together.
    |                         Token-based Sanctum (Bearer header) does not need
    |                         credentials support, so this is correct.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['X-Idempotency-Replayed'],

    'max_age' => 86400, // cache preflight for 24 h

    'supports_credentials' => false,

];
