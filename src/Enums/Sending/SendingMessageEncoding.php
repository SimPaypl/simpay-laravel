<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Sending;

enum SendingMessageEncoding: string
{
    case GSM7 = 'GSM-7';
    case UCS2 = 'UCS-2';
}
