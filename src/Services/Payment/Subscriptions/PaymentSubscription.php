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

    public function createBlik(): CreateBlikSubscription
    {
        return (new CreateBlikSubscription());
    }

    public function blikAutoPayment(string $subscriptionId): BlikAutoPayment
    {
        return (new BlikAutoPayment($subscriptionId));
    }
}
