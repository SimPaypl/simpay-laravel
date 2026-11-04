<?php

namespace SimPay\Laravel\Responses\Sending\Calculator;

use SimPay\Laravel\Enums\Sending\SendingMessageEncoding;

final readonly class SendingCalculatedMessageResponse
{
    public function __construct(
        public bool $isValid,
        public int $smsSegmentsCount,
        public int $maxSegmentsCount,
        public int $segmentLength,
        public SendingMessageEncoding $encoding,
        public int $charsCount,
        public ?float $estimatedPrice, // null when content is invalid
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['isValid'],
            $json['smsSegmentsCount'],
            $json['maxSegmentsCount'],
            $json['segmentLength'],
            SendingMessageEncoding::from($json['encoding']),
            $json['charsCount'],
            $json['estimatedPriceNet'],
        );
    }
}
