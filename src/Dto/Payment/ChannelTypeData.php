<?php

namespace SimPay\Laravel\Dto\Payment;

readonly final class ChannelTypeData
{
    public function __construct(
        private bool $blik = true,
        private bool $transfer = true,
        private bool $cards = true,
        private bool $ewallets = true,
        private bool $paypal = true,
        private bool $paysafe = true,
        private bool $latam = true,
        private bool $postponed = false,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'blik' => $this->blik,
            'transfer' => $this->transfer,
            'cards' => $this->cards,
            'ewallets' => $this->ewallets,
            'paypal' => $this->paypal,
            'paysafe' => $this->paysafe,
            'latam' => $this->latam,
            'postponed' => $this->postponed,
        ];
    }
}
