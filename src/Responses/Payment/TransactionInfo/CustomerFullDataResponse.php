<?php

namespace SimPay\Laravel\Responses\Payment\TransactionInfo;

final readonly class CustomerFullDataResponse
{
    public function __construct(
        public ?string $name = null,
        public ?string $surname = null,
        public ?string $street = null,
        public ?string $building = null,
        public ?string $flat = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $postalCode = null,
        public ?string $country = null,
        public ?string $company = null,
    )
    {
    }

    public static function from(array|null $json): self
    {
        return new self(
            $json['name'] ?? null,
            $json['surname'] ?? null,
            $json['street'] ?? null,
            $json['building'] ?? null,
            $json['flat'] ?? null,
            $json['city'] ?? null,
            $json['region'] ?? null,
            $json['postalCode'] ?? null,
            $json['country'] ?? null,
            $json['company'] ?? null,
        );
    }
}