<?php

namespace SimPay\Laravel\Responses\DirectBilling\AmountCalculator;

final readonly class AmountResponse
{
    public function __construct(
        public ?float $net,
        public ?float $gross,
    )
    {
    }

    public static function from(array $json)
    {
        return new self(
            $json['net'] ?? null,
            $json['gross'] ?? null,
        );
    }
}