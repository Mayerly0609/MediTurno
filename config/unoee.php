<?php

return [
    'wsdl_url' => env('UNOEE_WSDL_URL'),
    'database' => env('UNOEE_DATABASE'),
    'company' => env('UNOEE_COMPANY'),

    'timeout' => (int) env('UNOEE_SOAP_TIMEOUT', 600),

    'ssl_verify' => filter_var(
        env('UNOEE_SSL_VERIFY', true),
        FILTER_VALIDATE_BOOL
    ),

    'trace' => filter_var(
        env('UNOEE_SOAP_TRACE', false),
        FILTER_VALIDATE_BOOL
    ),
];
