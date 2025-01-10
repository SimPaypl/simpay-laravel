<?php

namespace SimPay\Laravel\Responses\Payment\TransactionInfo;

final readonly class CustomerResponse
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $ip = null,
    )
    {
    }

    public static function from(array|null $json): self
    {
        return new self(
            $json['name'] ?? null,
            $json['email'] ?? null,
            $json['ip'] ?? null,
        );
    }
}