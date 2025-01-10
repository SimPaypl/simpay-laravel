<?php

namespace SimPay\Laravel\Responses\Payment\TransactionInfo;

final readonly class CartItemResponse
{
    public function __construct(
        public string  $name,
        public int     $quantity,
        public float   $price,
        public ?string $producer = null,
        public ?string $category = null,
        public ?string $code = null,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            $json['name'],
            $json['quantity'],
            $json['price'],
            $json['producer'],
            $json['category'],
            $json['code'],
        );
    }
}