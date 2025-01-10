<?php

namespace SimPay\Laravel\Responses\Payment\TransactionInfo;

final readonly class RedirectsResponse
{
    public function __construct(
        public ?string $success = null,
        public ?string $failure = null,
    )
    {
    }

    public static function from(array|null $json): self
    {
        return new self(
            $json['success'] ?? null,
            $json['failure'] ?? null,
        );
    }
}