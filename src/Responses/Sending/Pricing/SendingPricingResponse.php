<?php

namespace SimPay\Laravel\Responses\Sending\Pricing;

final readonly class SendingPricingResponse
{
    /**
     * @param SendingPricingItemResponse[] $pricing
     * @param SendingPricingItemResponse $current
     */
    public function __construct(
        public array $pricing,
        public SendingPricingItemResponse $current,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            array_map(fn(array $item) => SendingPricingItemResponse::from($item), $json['pricing']),
            SendingPricingItemResponse::from($json['current']),
        );
    }
}
