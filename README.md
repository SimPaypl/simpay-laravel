# SimPay Laravel package

(c) 2025 Payments Solution Sp. z o.o.

## Documentation and examples:
Please see https://simpaypl.gitbook.io/simpay-laravel

## Installation:
Installation via Composer:
```
composer require simpaypl/laravel
```

Optionally you can publish config file:
```
php artisan vendor:publish --tag=simpay-config
```

You should put these parameters in your .env file:
```
SIMPAY_BEARER_TOKEN=YOUR_SIMPAY_API_TOKEN
SIMPAY_SMS_SERVICE_ID=YOUR_SMS_SERVICE_ID
SIMPAY_DIRECT_BILLING_SERVICE_ID=YOUR_DIRECTBILLING_SERVICE_ID
SIMPAY_DIRECT_BILLING_SERVICE_HASH=YOUR_DIRECTBILLING_SERVICE_HASH
SIMPAY_PAYMENT_SERVICE_ID=YOUR_PAYMENT_SERVICE_ID
SIMPAY_PAYMENT_SERVICE_HASH=YOUR_PAYMENT_SERVICE_HASH
```
