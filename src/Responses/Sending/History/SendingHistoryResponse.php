<?php

namespace SimPay\Laravel\Responses\Sending\History;

use Illuminate\Support\Carbon;
use SimPay\Laravel\Enums\Sending\SendingFailureReason;
use SimPay\Laravel\Enums\Sending\SendingHistoryStatus;
use SimPay\Laravel\Enums\Sending\SendingMessageEncoding;

final readonly class SendingHistoryResponse
{
    public function __construct(
        public string $sendingId,
        public SendingHistoryStatus $status,
        public ?string $override,
        public string $msisdn,
        public string $content,
        public ?string $control,
        public ?int $charsCount,
        public ?int $smsSegmentsCount,
        public ?SendingMessageEncoding $encoding,
        public ?float $netPrice,
        public ?SendingFailureReason $failureReason,
        public ?Carbon $sentAt,
        public ?Carbon $deliveredAt,
        public Carbon $createdAt,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['sendingId'],
            SendingHistoryStatus::from($json['status']),
            $json['override'],
            $json['msisdn'],
            $json['content'],
            $json['control'],
            $json['charsCount'],
            $json['smsSegmentsCount'],
            match ($json['encoding']) {
                'GSM7', 'GSM-7' => SendingMessageEncoding::GSM7,
                'UCS2', 'UCS-2' => SendingMessageEncoding::UCS2,
                default => null,
            },
            $json['netPrice'],
            SendingFailureReason::tryFrom((string)$json['failureReason']),
            !empty($json['sentAt']) ? Carbon::parse($json['sentAt']) : null,
            !empty($json['deliveredAt']) ? Carbon::parse($json['deliveredAt']) : null,
            Carbon::parse($json['createdAt']),
        );
    }
}
