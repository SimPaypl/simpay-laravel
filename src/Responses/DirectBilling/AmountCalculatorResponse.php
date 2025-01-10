<?php

namespace SimPay\Laravel\Responses\DirectBilling;

use SimPay\Laravel\Responses\DirectBilling\AmountCalculator\AmountResponse;

final readonly class AmountCalculatorResponse
{
    public function __construct(
        public ?AmountResponse $orange,
        public ?AmountResponse $play,
        public ?AmountResponse $tMobile,
        public ?AmountResponse $plus,
        public ?AmountResponse $netia,
    )
    {
    }

    public static function from(array $json)
    {
        return new self(
            $json['orange'] ? AmountResponse::from($json['orange']) : null,
            $json['play'] ? AmountResponse::from($json['play']) : null,
            $json['t-mobile'] ? AmountResponse::from($json['t-mobile']) : null,
            $json['plus'] ? AmountResponse::from($json['plus']) : null,
            $json['netia'] ? AmountResponse::from($json['netia']) : null,
        );
    }
}