<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', '/shared-login/*'], // Added /shared-login/*

    'allowed_methods' => ['*'],

    // It's recommended to be specific with origins in production.
    // Use environment variables to define these for flexibility.
    'allowed_origins' => [
        // env('FRONTEND_URL', 'http://localhost:3000'), // Example for a SPA frontend
        env('FOODPANDA_APP_URL', 'http://sf-foodpanda-app.test'), // Allow foodpanda-app
        env('ECOMMERCE_APP_URL', 'http://sf-ecommerce-app.test'), // Allow self for SPA-like behavior if any
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Consider being more specific, e.g., ['Content-Type', 'X-Requested-With', 'Authorization']

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Required for Sanctum cookie-based auth & cross-domain requests

];
