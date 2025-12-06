<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\Subscriptions;

final readonly class BlikSubscriptionCreatedResponse
{
    public function __construct(
        public string $subscriptionId,
        public string $aliasId,
    )
    {
    }
}