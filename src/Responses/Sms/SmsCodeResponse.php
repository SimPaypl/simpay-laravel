<?php

namespace SimPay\Laravel\Responses\Sms;

use Illuminate\Support\Carbon;

final readonly class SmsCodeResponse
{
    public function __construct(
        public bool $used,
        public string $code,
        public bool $test,
        public string $from,
        public int $number,
        public float $value,
        public ?Carbon $usedAt = null,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            (bool)$json['used'],
            $json['code'],
            (bool)$json['test'],
            $json['from'],
            $json['number'],
            $json['value'],
            !empty($json['used_at']) ? Carbon::parse($json['used_at']) : null,
        );
    }
}