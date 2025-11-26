<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\Subscriptions;

use Carbon\Carbon;
use SimPay\Laravel\Enums\Payment\Subscription\SubscriptionMode;
use SimPay\Laravel\Enums\Payment\Subscription\SubscriptionStatus;

final readonly class PaymentSubscriptionResponse
{
    public function __construct(
        public string $id,
        public SubscriptionStatus $status,
        public SubscriptionMode $mode,
        public ?PaymentSubscriptionBlikResponse $blik,
        public ?string $frequency,
        public ?Carbon $initiationDate,
        public ?float $totalAmountLimit,
        public ?int $totalTransactionsLimit,
        public ?PaymentSubscriptionCancelledResponse $cancelled,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    )
    {
    }

    public function from(array $data): self
    {
        return new self(
            id: $data['id'],
            status: SubscriptionStatus::from($data['status']),
            mode: SubscriptionMode::from($data['mode']),
            blik: $data['blik'] ? PaymentSubscriptionBlikResponse::from($data['blik']) : null,
            frequency: $data['frequency'],
            initiationDate: $data['initiation_date'] ? Carbon::parse($data['initiation_date']) : null,
            totalAmountLimit: $data['total_amount_limit'],
            totalTransactionsLimit: $data['total_transactions_limit'],
            cancelled: $data['cancelled'] ? PaymentSubscriptionCancelledResponse::from($data['cancelled']) : null,
            createdAt: Carbon::parse($data['created_at']),
            updatedAt: Carbon::parse($data['updated_at']),
        );
    }
}