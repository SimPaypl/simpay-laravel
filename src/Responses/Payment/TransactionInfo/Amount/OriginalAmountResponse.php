<?php

namespace SimPay\Laravel\Responses\Payment\TransactionInfo\Amount;

final readonly class OriginalAmountResponse
{
    public function __construct(
        public float  $value,
        public string $currency,
        public ?string $pln_rate = null,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['value'],
            $json['currency'],
            $json['pln_rate'] ?? null,
        );
    }
}