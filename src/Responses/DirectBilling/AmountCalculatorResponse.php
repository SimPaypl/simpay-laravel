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
            !empty($json['orange']) ? AmountResponse::from($json['orange']) : null,
            !empty($json['play']) ? AmountResponse::from($json['play']) : null,
            !empty($json['t-mobile']) ? AmountResponse::from($json['t-mobile']) : null,
            !empty($json['plus']) ? AmountResponse::from($json['plus']) : null,
            !empty($json['netia']) ? AmountResponse::from($json['netia']) : null,
        );
    }
}