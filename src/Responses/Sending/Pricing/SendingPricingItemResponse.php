<?php

namespace SimPay\Laravel\Responses\Sending\Pricing;

final readonly class SendingPricingItemResponse
{
    public function __construct(
        public float $price,
        public ?int $upTo,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['price'],
            $json['upTo'],
        );
    }
}
