<?php

namespace SimPay\Laravel\Responses\Sending\Calculator;

final readonly class SendingCalculatedMassItemResponse
{
    public function __construct(
        public ?string $msisdn,
        public ?string $override,
        public string $content,
        public SendingCalculatedMessageResponse $result,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['msisdn'],
            $json['override'],
            $json['content'],
            SendingCalculatedMessageResponse::from($json['result']),
        );
    }
}
