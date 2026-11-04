<?php

namespace SimPay\Laravel\Responses\Sending;

final readonly class SendingBalanceResponse
{
    public function __construct(
        public float $balance,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['balance'],
        );
    }
}
