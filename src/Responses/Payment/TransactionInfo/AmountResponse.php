<?php

namespace SimPay\Laravel\Responses\Payment\TransactionInfo;

final readonly class AmountResponse
{
    public function __construct(
        public float  $value,
        public string $currency,
        public ?float $commission = null,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['value'],
            $json['currency'],
            $json['commission'],
        );
    }
}