<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Payment\Subscription;

enum SubscriptionStatus: string
{
    case Pending = 'subscription_pending';
    case Active = 'subscription_active';
    case Cancelled = 'subscription_cancelled';
    case Expired = 'subscription_expired';
    case Finished = 'subscription_finished';
    case Fraudulent = 'subscription_fraudulent';
}
