<?php

namespace SimPay\Laravel\Responses\Sending\Send;

final readonly class MassSendingQueuedResponse
{
    public function __construct(
        public string $massSendingId,
        public int $messagesCount,
        public float $estimatedCost,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['massSendingId'],
            $json['messagesCount'],
            $json['estimatedCost'],
        );
    }
}
