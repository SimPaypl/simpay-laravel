<?php
declare(strict_types=1);

namespace SimPay\Laravel\Notifications;

final readonly class SimPayMessage
{
    /**
     * @param string|null $override approved sender name, falls back to config('simpay.sending.override')
     */
    public function __construct(
        public string  $content,
        public ?string $override = null,
        public ?string $control = null,
    )
    {
    }
}
