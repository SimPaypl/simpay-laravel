<?php
declare(strict_types=1);

namespace SimPay\Laravel\Services\Payment\BlikAliases;

use SimPay\Laravel\Services\Service;

class PaymentBlikAlias extends Service
{
    public function list(): BlikAliasesList
    {
        return (new BlikAliasesList());
    }

    public function unregister(): UnregisterAlias
    {
        return (new UnregisterAlias());
    }
}
