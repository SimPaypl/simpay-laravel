<?php

namespace SimPay\Laravel\Dto\Payment;

readonly final class AntiFraudData
{
    public function __construct(
        private ?string $userAgent = null,
        private ?string $steamId64 = null,
        private ?string $minecraftUsername = null,
        private ?string $minecraftUuid = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'useragent' => $this->userAgent,
            'steamid' => $this->steamId64,
            'mcusername' => $this->minecraftUsername,
            'mcid' => $this->minecraftUuid,
        ];
    }
}