<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the settings for handling Cross-Origin Resource Sharing (CORS).
    | CORS allows your web application to communicate with resources on different domains.
    | These settings let you control which origins, methods, and headers are permitted.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // Paths that should allow CORS. You can specify API routes or specific endpoints.
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // HTTP methods that are allowed for CORS requests.
    // Example: ['GET', 'POST', 'PUT', 'DELETE']
    // Use '*' to allow all HTTP methods.
    'allowed_methods' => ['*'],

    // Origins that are allowed to access your resources.
    // Example: ['https://example.com', 'https://another-domain.com']
    // Use '*' to allow all origins.
    'allowed_origins' => ['*'],

    // Patterns for allowed origins using regular expressions.
    // Example: ['*.example.com'] to allow all subdomains of example.com.
    'allowed_origins_patterns' => [],

    // Headers that are allowed in the incoming CORS request.
    // Example: ['Content-Type', 'Authorization']
    // Use '*' to allow all headers.
    'allowed_headers' => ['*'],

    // Headers that should be exposed in the response for CORS requests.
    // Example: ['Authorization', 'X-Custom-Header']
    'exposed_headers' => [],

    // Maximum age (in seconds) the CORS request will be cached by the browser.
    // Set to 0 to disable caching.
    'max_age' => 0,

    // Whether to support credentials like cookies or HTTP authentication.
    // Set to true if your application needs to allow credentials in CORS requests.
    'supports_credentials' => false,

];
