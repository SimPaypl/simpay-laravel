<?php

namespace SimPay\Laravel\Responses\Sending\History;

use Illuminate\Support\Carbon;
use SimPay\Laravel\Enums\Sending\SendingMassStatus;

final readonly class MassSendingResponse
{
    /**
     * @param array<string, int>|null $statusesCount history status value => count; only filled by mass sending details
     */
    public function __construct(
        public string $massSendingId,
        public string $name,
        public SendingMassStatus $status,
        public ?string $control,
        public int $messagesCount,
        public float $netPrice,
        public ?Carbon $scheduledAt,
        public ?Carbon $finishedAt,
        public Carbon $createdAt,
        public ?array $statusesCount = null,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['massSendingId'],
            $json['name'],
            SendingMassStatus::from($json['status']),
            $json['control'],
            $json['messagesCount'],
            $json['netPrice'],
            !empty($json['scheduledAt']) ? Carbon::parse($json['scheduledAt']) : null,
            !empty($json['finishedAt']) ? Carbon::parse($json['finishedAt']) : null,
            Carbon::parse($json['createdAt']),
            $json['statusesCount'] ?? null,
        );
    }
}
