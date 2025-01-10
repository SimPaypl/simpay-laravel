<?php

namespace SimPay\Laravel\Dto\Payment;

readonly final class CustomerData
{
    public function __construct(
        private ?string $name = null,
        private ?string $email = null,
        private ?string $ip = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'ip' => $this->ip,
        ];
    }
}