<?php

namespace SimPay\Laravel\Responses\Sending;

use Illuminate\Support\Carbon;

final readonly class SendingOverrideResponse
{
    public function __construct(
        public string $override,
        public bool $isActive,
        public Carbon $createdAt,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['override'],
            $json['isActive'],
            Carbon::parse($json['createdAt']),
        );
    }
}
