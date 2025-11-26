<?php
declare(strict_types=1);

namespace SimPay\Laravel\Services\Payment\Subscriptions;

use SimPay\Laravel\Services\Service;

class PaymentSubscription extends Service
{
    public function list(): SubscriptionsList
    {
        return (new SubscriptionsList());
    }
}