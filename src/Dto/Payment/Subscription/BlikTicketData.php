<?php

namespace SimPay\Laravel\Dto\Payment\Subscription;

use SimPay\Laravel\Enums\Payment\BlikLevel0TicketType;

readonly final class BlikTicketData
{
    public function __construct(
        private string $ticket,
        private BlikLevel0TicketType $ticketType = BlikLevel0TicketType::T6,
    )
    {
    }

    public function toArray(): array
    {
        return [
            $this->ticketType->value => $this->ticket,
        ];
    }
}