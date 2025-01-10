<?php

namespace SimPay\Laravel\Responses\Sms;

final readonly class SmsNumberResponse
{
    public function __construct(
        public int $number,
        public float $value,
        public float $valueGross,
        public bool $adult,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['number'],
            $json['value'],
            $json['value_gross'],
            $json['adult'],
        );
    }
}