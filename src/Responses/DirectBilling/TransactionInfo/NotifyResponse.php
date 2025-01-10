<?php

namespace SimPay\Laravel\Responses\DirectBilling\TransactionInfo;

use Illuminate\Support\Carbon;

final readonly class NotifyResponse
{
    public function __construct(
        public bool    $isSend,
        public ?Carbon $lastSendAt,
        public int     $count = 0,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['is_send'],
            empty($json['last_send_at']) ? null : Carbon::parse($json['last_send_at']),
            $json['count'] ?? 0,
        );
    }
}