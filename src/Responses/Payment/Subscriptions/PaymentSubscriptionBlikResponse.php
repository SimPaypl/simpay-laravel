<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\Subscriptions;

use SimPay\Laravel\Enums\Payment\Subscription\BlikModel;
use SimPay\Laravel\Responses\Payment\BlikAlias\PaymentBlikAliasResponse;

final readonly class PaymentSubscriptionBlikResponse
{
    public function __construct(
        public BlikModel                $model,
        public PaymentBlikAliasResponse $alias,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            model: BlikModel::from($data['model']),
            alias: PaymentBlikAliasResponse::from($data['alias']),
        );
    }
}