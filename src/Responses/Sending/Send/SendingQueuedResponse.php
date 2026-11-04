<?php

namespace SimPay\Laravel\Responses\Sending\Send;

final readonly class SendingQueuedResponse
{
    public function __construct(
        public string $sendingId,
        public float $estimatedCost,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['sendingId'],
            $json['estimatedCost'],
        );
    }
}
