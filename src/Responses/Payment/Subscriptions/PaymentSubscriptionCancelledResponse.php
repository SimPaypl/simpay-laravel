<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\Subscriptions;

use SimPay\Laravel\Enums\Payment\Subscription\SubscriptionCancelledBy;

final readonly class PaymentSubscriptionCancelledResponse
{
    public function __construct(
        public SubscriptionCancelledBy $cancelledBy,
        public ?string                 $reason,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            cancelledBy: SubscriptionCancelledBy::from($data['by']),
            reason: $data['reason'],
        );
    }
}