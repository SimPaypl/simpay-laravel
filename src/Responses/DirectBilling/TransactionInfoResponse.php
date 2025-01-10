<?php

namespace SimPay\Laravel\Responses\DirectBilling;

use Illuminate\Support\Carbon;
use SimPay\Laravel\Enums\DirectBilling\AmountType;
use SimPay\Laravel\Enums\DirectBilling\MobileCarrier;
use SimPay\Laravel\Enums\DirectBilling\TransactionStatus;
use SimPay\Laravel\Responses\DirectBilling\TransactionInfo\NotifyResponse;

final readonly class TransactionInfoResponse
{
    public function __construct(
        public string            $id,
        public TransactionStatus $status,
        public ?string           $phoneNumber,
        public ?string           $control,
        public float             $value,
        public float             $valueNet,
        public float             $valueCreated,
        public AmountType        $valueCreatedType,
        public ?MobileCarrier    $carrier = null,
        public NotifyResponse    $notify,
        public ?float            $score = null,
        public ?Carbon           $createdAt = null,
        public ?Carbon           $updatedAt = null,
    )
    {
    }

    public static function from(array $json)
    {
        return new self(
            $json['id'],
            TransactionStatus::from($json['status']),
            $json['phoneNumber'],
            $json['control'] ?? null,
            $json['value'] ?? 0.0,
            $json['value_net'] ?? 0.0,
            $json['value_created'] ?? 0.0,
            $json['value_created_type'] ? AmountType::from(strtolower($json['value_created_type'])) : null,
            $json['operator'] ? MobileCarrier::from($json['operator']) : null,
            NotifyResponse::from($json['notify']),
            $json['score'] ?? null,
            $json['created_at'] ? Carbon::parse($json['created_at']) : null,
            $json['updated_at'] ? Carbon::parse($json['updated_at']) : null,
        );
    }
}