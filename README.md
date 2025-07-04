# SimPay Laravel package

(c) 2025 Payments Solution Sp. z o.o.

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

## Base usage
SimPay plugin is accessible through two methods:
1. Facade - to use facade you should do:
```php
use SimPay\Laravel\Facades\SimPay;

SimPay::payment();
SimPay::directBilling();
SimPay::sms();
```

2. Using app() helper:
```php
app('simpay')->payment();
app('simpay')->directBilling();
app('simpay')->sms();
```

## Important information
Our library does not create any routes for you - you have to manually 
register routes for IPN handling. 
With this library you can create transactions, get transaction information and validate IPN signature. 

## Exceptions
Every exception in our library is extended from
```php
\SimPay\Laravel\Exceptions\SimPayException
```

- When response from our API is 401 or 403, `\SimPay\Laravel\Exceptions\AuthorizationException` is thrown;
- When response from our API is 404, `\SimPay\Laravel\Exceptions\ResourceNotFoundException` is thrown (this will be also thrown, when SMS code does not exists);
- When response from our API is 422, `\SimPay\Laravel\Exceptions\ValidationFailedException` is thrown (you can get errors by using `$exception->errors()`.


## Payments

### Generate payment

```php

SimPay::payment()->generate()
    ->amount(15.00)
    // rest of fields is optional
    ->returns(
        new \SimPay\Laravel\Dto\ReturnData(
            'successUrl',
            'failedUrl',
        ),
    )
    ->billing(
        new \SimPay\Laravel\Dto\Payment\CustomerFullData(
            'First name',
            'Surname',
            'Street',
            'House number',
            'Flat',
            'City',
            'Region',
            'Post Code',
            'Country code',
            'Company',
        )
    )
    ->shipping(
        new \SimPay\Laravel\Dto\Payment\CustomerFullData(
            'First name',
            'Surname',
            'Street',
            'House number',
            'Flat',
            'City',
            'Region',
            'Post Code',
            'Country code',
            'Company',
        )
    )
    ->customer(
        new \SimPay\Laravel\Dto\Payment\CustomerData(
            'Name',
            'Email',
            'IP address',
            'Country Code (eg. PL, US, CY, GB)',
        )
    )
    ->antifraud(
        new \SimPay\Laravel\Dto\Payment\AntiFraudData(
            'UserAgent',
            'SteamID64',
            'Minecraft player username',
            'Minecraft player uuid',
        )
    )
    ->control('Control Data (for your integration, ex. your id from database)')
    ->description('Transaction Description')
    ->currency('Currency Code (default: PLN)')
    ->directChannel('blik')
    ->channelTypes(new \SimPay\Laravel\Dto\Payment\ChannelTypeData(
        blik: true,
        transfer: true,
        cards: true,
        ewallets: true,  
        paypal: true,
        paysafe: true,
        latam: true,
    ))
    // make() is required to create payment.
    ->make();
```

Response will be `SimPay\Laravel\Responses\TransactionGeneratedResponse`.
You can get url and transactionId using these public parameters:
- `url`
- `transactionId`

### Get transaction info

```php
SimPay::payment()->transactionInfo('Transaction ID');
```

Response will be `SimPay\Laravel\Responses\Payment\TransactionInfoResponse`.
Public parameters:
- `id` (string)
- `status` (See below)
- `amount` (See below)
- `channel` (null or string)
- `control` (null or string)
- `description` (null or string)
- `redirects` (See below)
- `customer` (See below)
- `billing` (See below)
- `shipping` (See below)
- `cart` (See below)
- `additional` (array or null)
- `paidAt` (Carbon instance or null)
- `expiresAt` (Carbon instance or null)
- `createdAt` (Carbon instance)
- `updatedAt` (Carbon instance)
- `payerTransactionId` (string) - payment identifier which is shown to payer on gateway and in mail messages

`status` is an enum located in `SimPay\Laravel\Enums\Payment\TransactionStatus`.

Enum cases:
- `New`
- `Generated`
- `Confirmed`
- `Paid`
- `Failure`
- `Expired`
- `Refunded`

`amount` is an instance of `SimPay\Laravel\Responses\Payment\TransactionInfo\AmountResponse`.
Public parameters:
- `value` (float)
- `currency` (string)
- `commission` (float or null)
- `commissionCurrency` (string or null)
- `original` (see below)

`amount.original` is an instance of `SimPay\Laravel\Responses\Payment\TransactionInfo\Amount\OriginalAmountResponse`.
Public parameters:
- `value` (float)
- `currency` (string)
- `pln_rate` (string or null)

`redirects` is an instance of `SimPay\Laravel\Responses\Payment\TransactionInfo\RedirectsResponse`.
Public parameters (string or null):
- `success`
- `failure`

`customer` is an instance of `SimPay\Laravel\Responses\Payment\TransactionInfo\CustomerResponse`. 
Public parameters (string or null):
- `name`
- `email`
- `ip`
- `country`

`billing` and `shipping` are instances of `SimPay\Laravel\Responses\Payment\TransactionInfo\CustomerFullDataResponse`.
Public parameters (string or null):
- `name` 
- `surname`
- `street`
- `building`
- `flat`
- `city`
- `region`
- `postalCode`
- `country`
- `company`

`cart` is an array of `SimPay\Laravel\Responses\Payment\TransactionInfo\CartItemResponse`.
Public parameters of single cart item:
- `name` (string)
- `quantity` (int)
- `price` (float)
- `producer` (string or null)
- `category` (string or null)
- `code` (string or null)

### Get payment channels

```php
SimPay::payment()->channels();
```

Response is an array of `SimPay\Laravel\Responses\Payment\ServiceChannel\ChannelResponse`.
Public parameters:
- `id` (string)
- `name` (string)
- `type` (string)
- `img` (string)
- `commission` (float or null)
- `currencies` (array)
- `amount` (see below)

`amount` is an instance of `SimPay\Laravel\Responses\Payment\ServiceChannel\ChannelAmountResponse`.
Public parameters (float):
- `min`
- `max`

### Signature validator
```php
SimPay::payment()->ipnSignatureValid(request());
```

Response is boolean. True means signature is valid. False - invalid.

### Get service currencies
```php
SimPay::payment()->currencies();
```
Response is an array of `SimPay\Laravel\Responses\Payment\ServiceCurrency\CurrencyResponse`.
Public parameters:
- `iso` (string)
- `plnRate` (string)
- `nbpTable` (string or null)
- `prefix` (string or null)
- `suffix` (string or null)
- `updatedAt` (Carbon instance or null)

### Make BLIK Level 0 transaction
```php
// at first generate payment

// only required fields are shown
$payment = SimPay::payment()->generate()
    ->amount(15.00)
    ->customer(
        new \SimPay\Laravel\Dto\Payment\CustomerData(
            email: 'Email',
            ip: 'IP address',
            countryCode: 'PL', // for BLIK it must be PL
        )
    )
    ->antifraud(
        new \SimPay\Laravel\Dto\Payment\AntiFraudData(
            userAgent: 'UserAgent (REQUIRED)',
        )
    )
    ->currency('PLN')
    ->directChannel('blik-level0') // it must be set to blik-level0
    ->make();

// Now make Level 0 call
try {
    $success = SimPay::payment()->blikLevel0()
    ->ticket('111222') // BLIK code
    ->ticketType(\SimPay\Laravel\Enums\Payment\BlikLevel0TicketType::T6) // for now, only T6 codes are supported
    ->transaction($payment) // you may pass full TransactionGenerateResponse or just transactionId
    ->make();
}
catch(\SimPay\Laravel\Exceptions\BlikLevel0\InvalidBlikTicketException $exception) {
    // notify user that BLIK ticket is not valid
}
catch(\SimPay\Laravel\Exceptions\SimPayException $exception) {
    // other error
}
```
If ticket has been accepted, $success will be true.

**WARNING**: This does not mean that transaction is finished, you still need to listen to our IPN messages.

If ticket code is not valid, two exceptions may be thrown:
- \SimPay\Laravel\Exceptions\BlikLevel0\InvalidBlikTicketException
- \SimPay\Laravel\Exceptions\SimPayException


## Direct Billing

### Generate transaction
```php
SimPay::directBilling()->generate()
    ->amount(15.00)
    ->amountType(\SimPay\Laravel\Enums\DirectBilling\AmountType::Net)
    // other are optionals
    ->returns(new \SimPay\Laravel\Dto\ReturnData(
        'https://success.pl',
        'https://failure.com',
    ))
    ->control('Control field (ex. your database id from your integration)')
    ->description('Transaction description')
    ->phoneNumber('+48123123123')
    ->steamId('SteamID64')
    ->email('Email')
    // make is required to generate transaction
    ->make();
```

`amountType()` accepts an Enum `SimPay\Laravel\Enums\DirectBilling`.
Enum cases:
- `Required`
- `Net`
- `Gross` (default)

Response will be `SimPay\Laravel\Responses\TransactionGeneratedResponse`.
You can get url and transactionId using these public parameters:
- `url`
- `transactionId`

### Get transaction info
```php
SimPay::directBilling()->transactionInfo('Transaction ID');
```

Response will be an instance of `SimPay\Laravel\Responses\DirectBilling\TransactionInfoResponse`.
Public parameters:
- `id` (string)
- `status` (see below)
- `phoneNumber` (string or null)
- `control` (string or null)
- `value` (float)
- `valueNet` (float)
- `valueCreated` (float)
- `valueCreatedType` (see below)
- `carrier` (see below)
- `notify` (see below)
- `score` (float or null)
- `createdAt` (Carbon instance or null)
- `updatedAt` (Carbon instance or null)

`status` is an Enum located in `SimPay\Laravel\Enums\DirectBilling\TransactionStatus`.
Enum cases:
- `New`
- `Confirmed`
- `Paid`
- `Rejected`
- `Cancelled`
- `GenerateError`

`valueCreatedType` is an Enum located in `SimPay\Laravel\Enums\DirectBilling\AmountType`.
Enum cases:
- `Required`
- `Net`
- `Gross`

`carrier` is null or an Enum located in `SimPay\Laravel\Enums\DirectBilling\MobileCarrier`.
Enum cases:
- `Orange`
- `Play`
- `TMobile`
- `Plus`
- `Netia`

`notify` is an instance of `SimPay\Laravel\Responses\DirectBilling\TransactionInfo\NotifyResponse`.
Public parameters:
- `isSend` (bool)
- `lastSendAt` (Carbon instance or null)
- `count` (int)

### Calculate amount
```php
SimPay::directBilling()->calculate(amount: 15.00);
```

Response is an instance of `SimPay\Laravel\Responses\DirectBilling\AmountCalculatorResponse`.
Public parameters:
- `orange`
- `play`
- `tMobile`
- `plus`
- `netia`

Every parameter is an instance of `SimPay\Laravel\Responses\DirectBilling\AmountCalculator\AmountResponse`.
Public parameters:
- `net` (float or null)
- `gross` (float or null)

### Signature validator
```php
SimPay::directBilling()->ipnSignatureValid(request());
```

Response is boolean. True means signature is valid. False - invalid.

## SMS

### Get service numbers
```php
SimPay::sms()->numbers();
```

Response is an array of `SimPay\Laravel\Responses\Sms\SmsNumberResponse`.
Public parameters of `SmsNumberResponse`:
- `number` (int)
- `value` (float)
- `valueGross` (float)
- `adult` (bool)

### Check code
```php
SimPay::sms()->check('SMS_CODE', 'sms number as int (optional)');
```

Response is an instance of `SimPay\Laravel\Responses\Sms\SmsCodeResponse`.
Public parameters:
- `used` (bool)
- `code` (string)
- `test` (bool)
- `from` (string)
- `number` (int)
- `value` (float)
- `usedAt` (Carbon instance or null)
