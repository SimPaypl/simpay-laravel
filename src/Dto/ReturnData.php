<?php

namespace SimPay\Laravel\Dto;

readonly final class ReturnData
{
    public function __construct(
        private ?string $success = null,
        private ?string $failure = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'failure' => $this->failure,
        ];
    }
}