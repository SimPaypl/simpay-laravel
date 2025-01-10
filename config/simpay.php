<?php

return [
    'bearer_token' => env('SIMPAY_BEARER_TOKEN'),
    'sms' => [
        'service_id' => env('SIMPAY_SMS_SERVICE_ID'),
    ],
    'direct_billing' => [
        'service_id' => env('SIMPAY_DIRECT_BILLING_SERVICE_ID'),
        'hash' => env('SIMPAY_DIRECT_BILLING_SERVICE_HASH'),
    ],
    'payment' => [
        'service_id' => env('SIMPAY_PAYMENT_SERVICE_ID'),
        'hash' => env('SIMPAY_PAYMENT_SERVICE_HASH'),
    ],
];
