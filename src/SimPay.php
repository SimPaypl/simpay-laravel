<?php

namespace SimPay\Laravel;

use SimPay\Laravel\Services\DirectBilling\DirectBilling;
use SimPay\Laravel\Services\Payment\Payment;
use SimPay\Laravel\Services\Sms\Sms;

class SimPay
{
    public const string VERSION = '1.2.0';

    public function payment(): Payment
    {
        return new Payment();
    }

    public function directBilling(): DirectBilling
    {
        return new DirectBilling();
    }

    public function sms(): Sms
    {
        return new Sms();
    }
}