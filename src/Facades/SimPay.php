<?php

namespace SimPay\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class SimPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'simpay';
    }
}
