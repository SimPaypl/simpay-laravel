<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Payment\BlikAlias;

enum BlikAliasStatus: string
{
    case PendingRegistration = 'alias_pending_registration';
    case Active = 'alias_active';
    case Expired = 'alias_expired';
    case Unregistered = 'alias_unregistered';
}
