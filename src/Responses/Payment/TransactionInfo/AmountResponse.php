<?php

namespace SimPay\Laravel\Responses\Payment\TransactionInfo;

use SimPay\Laravel\Responses\Payment\TransactionInfo\Amount\OriginalAmountResponse;

final readonly class AmountResponse
{
    public function __construct(
        public float  $value,
        public string $currency,
        public ?float $commission = null,
        public ?string $commissionCurrency = null,
        public ?OriginalAmountResponse $original = null,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['value'],
            $json['currency'],
            $json['commission'],
            $json['commission_currency'],
            !empty($json['original']) ? OriginalAmountResponse::from($json['original']) : null,
        );
    }
}