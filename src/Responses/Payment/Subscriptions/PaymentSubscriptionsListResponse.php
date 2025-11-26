<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\Subscriptions;

use SimPay\Laravel\Responses\PaginationResponse;

final readonly class PaymentSubscriptionsListResponse
{
    public function __construct(
        /** @var PaymentSubscriptionResponse[] $subscriptions */
        public array $subscriptions = [],
        public PaginationResponse $pagination,
    )
    {
    }

    public static function from(array $data): self
    {
        $subscriptions = [];
        foreach ($data['data'] as $subscription) {
            PaymentSubscriptionResponse::from($subscription);
        }

        return new self($subscriptions, PaginationResponse::from($data['pagination']));
    }
}