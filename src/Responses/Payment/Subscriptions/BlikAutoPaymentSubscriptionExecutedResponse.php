<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\Subscriptions;

final readonly class BlikAutoPaymentSubscriptionExecutedResponse
{
    public function __construct(
        public bool $needsUserConfirmation,
    )
    {
    }
}
