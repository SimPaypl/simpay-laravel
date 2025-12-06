<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Payment\BlikAlias;

enum BlikAliasType: string
{
    case PAYID = 'PAYID';
    case UID = 'UID';
}
