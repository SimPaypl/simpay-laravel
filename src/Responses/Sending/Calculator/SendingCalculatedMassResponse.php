<?php

namespace SimPay\Laravel\Responses\Sending\Calculator;

final readonly class SendingCalculatedMassResponse
{
    /**
     * @param SendingCalculatedMassItemResponse[]|null $validMessages null when not requested as detailed
     * @param SendingCalculatedMassItemResponse[]|null $invalidMessages null when not requested as detailed
     */
    public function __construct(
        public int $valid,
        public int $invalid,
        public int $totalMessagesCount,
        public float $totalPriceNet,
        public ?array $validMessages = null,
        public ?array $invalidMessages = null,
    )
    {
    }

    public static function from(array $json): self
    {
        $map = fn(?array $items) => $items === null ? null : array_map(fn(array $item) => SendingCalculatedMassItemResponse::from($item), $items);

        return new self(
            $json['valid'],
            $json['invalid'],
            $json['totalMessagesCount'],
            $json['totalPriceNet'],
            $map($json['detailed']['valid'] ?? null),
            $map($json['detailed']['invalid'] ?? null),
        );
    }
}
