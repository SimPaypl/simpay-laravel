<?php

namespace SimPay\Laravel\Dto\Payment\Subscription\BlikAutoPayment;

readonly final class BlikAutoPaymentAliasData
{
    public function __construct(
        private ?string $label = null,
        private ?bool   $noDelay = null,
    )
    {
    }

    public function toArray(): array
    {
        return array_filter([
            'label' => $this->label,
            'noDelay' => $this->noDelay,
        ]);
    }
}
