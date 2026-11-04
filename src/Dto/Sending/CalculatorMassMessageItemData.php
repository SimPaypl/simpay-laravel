<?php
declare(strict_types=1);

namespace SimPay\Laravel\Dto\Sending;

readonly final class CalculatorMassMessageItemData
{
    public function __construct(
        private string  $content,
        private ?string $msisdn = null,
        private ?string $override = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'msisdn' => $this->msisdn,
            'override' => $this->override,
        ];
    }
}
