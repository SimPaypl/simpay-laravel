<?php

namespace SimPay\Laravel\Dto\Payment;

readonly final class CartItemData
{
    public function __construct(
        private ?string $name = null,
        private ?int $quantity = null,
        private ?float $price = null,
        private ?string $producer = null,
        private ?string $category = null,
        private ?string $code = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'producer' => $this->producer,
            'category' => $this->category,
            'code' => $this->code,
        ];
    }
}