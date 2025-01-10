<?php

namespace SimPay\Laravel\Dto\Payment;

readonly final class CustomerFullData
{
    public function __construct(
        private ?string $name = null,
        private ?string $surname = null,
        private ?string $street = null,
        private ?string $building = null,
        private ?string $flat = null,
        private ?string $city = null,
        private ?string $region = null,
        private ?string $postalCode = null,
        private ?string $country = null,
        private ?string $company = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'street' => $this->street,
            'building' => $this->building,
            'flat' => $this->flat,
            'city' => $this->city,
            'region' => $this->region,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
            'company' => $this->company,
        ];
    }
}