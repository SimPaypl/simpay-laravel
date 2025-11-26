<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\Subscriptions;

use SimPay\Laravel\Enums\Payment\Subscription\BlikModel;
use SimPay\Laravel\Enums\Payment\Subscription\SubscriptionCancelledBy;
use SimPay\Laravel\Responses\Payment\BlikAlias\PaymentBlikAliasResponse;

final readonly class PaymentSubscriptionCancelledResponse
{
    public function __construct(
        public SubscriptionCancelledBy $cancelledBy,
        public ?string $reason,
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