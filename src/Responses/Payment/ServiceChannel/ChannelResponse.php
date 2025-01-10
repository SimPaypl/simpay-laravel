<?php

namespace SimPay\Laravel\Responses\Payment\ServiceChannel;

final readonly class ChannelResponse
{
    public function __construct(
        public string                $id,
        public string                $name,
        public string                $type,
        public string                $img,
        public ?float                $commission,
        public array                 $currencies,
        public ChannelAmountResponse $amount,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['id'],
            $json['name'],
            $json['type'],
            $json['img'],
            $json['commission'],
            $json['currencies'],
            ChannelAmountResponse::from($json['amount']),
        );
    }
}