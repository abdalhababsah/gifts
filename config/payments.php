<?php

return [
    'provider' => env('PAYMENT_PROVIDER', 'mpgs'),
    
    // Base URL for the MPGS gateway API (test environment)
    'base_url' => env('PAYMENT_GATEWAY_BASE_URL', 'https://test-mepspay.mtf.gateway.mastercard.com'),
    
    // MPGS merchant credentials
    'merchant_id' => env('PAYMENT_GATEWAY_MERCHANT_ID', 'TESTNITEST2'),
    
    // API credentials
    'api_username' => env('PAYMENT_GATEWAY_USERNAME', 'merchant.TESTNITEST2'),
    'api_password' => env('PAYMENT_GATEWAY_PASSWORD', 'ac63181fe688fe7ce3cf5a1f105a145a'),
    
    // Default currency
    'currency' => env('PAYMENT_CURRENCY', 'JOD'),
    
    // API endpoints and version
    'api_version' => env('PAYMENT_GATEWAY_API_VERSION', '100'),
    
    'endpoints' => [
        'create_session' => '/api/rest/version/{version}/merchant/{merchantId}/session',
        'pay' => '/api/rest/version/{version}/merchant/{merchantId}/order/{orderId}/transaction/{transactionId}',
        'retrieve_txn' => '/api/rest/version/{version}/merchant/{merchantId}/order/{orderId}/transaction/{transactionId}',
    ],
];