<?php

namespace SimPay\Laravel\Enums\DirectBilling;

enum AmountType: string
{
    case Required = 'required';
    case Net = 'net';
    case Gross = 'gross';
}
