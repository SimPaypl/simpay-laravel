<?php

namespace SimPay\Laravel\Responses\Payment\ServiceChannel;

final readonly class ChannelAmountResponse
{
    public function __construct(
        public float $min,
        public float $max,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['min'],
            $json['max'],
        );
    }
}