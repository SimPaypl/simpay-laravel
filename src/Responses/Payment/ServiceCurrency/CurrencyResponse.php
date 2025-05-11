<?php

namespace SimPay\Laravel\Responses\Payment\ServiceCurrency;

use Illuminate\Support\Carbon;

final readonly class CurrencyResponse
{
    public function __construct(
        public string  $iso,
        public string  $plnRate,
        public ?string $nbpTable,
        public ?string $prefix,
        public ?string $suffix,
        public ?Carbon  $updatedAt,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['iso'],
            $json['pln_rate'],
            $json['nbp_table'],
            $json['prefix'],
            $json['suffix'],
            Carbon::parse($json['updated_at']),
        );
    }
}