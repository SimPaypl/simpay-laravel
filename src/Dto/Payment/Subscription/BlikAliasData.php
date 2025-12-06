<?php

namespace SimPay\Laravel\Dto\Payment\Subscription;

use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasType;

readonly final class BlikAliasData
{
    public function __construct(
        private string        $value,
        private ?string       $label = null,
        private BlikAliasType $type = BlikAliasType::PAYID,
    )
    {
    }

    public function toArray(): array
    {
        return array_filter([
            'value' => $this->value,
            'type' => $this->type->value,
            'label' => $this->label,
        ]);
    }
}