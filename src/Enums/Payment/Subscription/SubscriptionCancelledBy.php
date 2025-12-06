<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Payment\Subscription;

enum SubscriptionCancelledBy: string
{
    case System = 'system';
    case Antifraud = 'antifraud';
    case Merchant = 'merchant';
    case Payer = 'payer';
    case Blik = 'blik';
}
