<?php

namespace SimPay\Laravel\Responses;

final readonly class TransactionGeneratedResponse
{
    public function __construct(
        public string $url,
        public string $transactionId,
    )
    {
    }
}