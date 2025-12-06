<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Payment\Subscription;

enum SubscriptionMode: string
{
    case Blik = 'BLIK';
    case Card = 'CARD';
}
