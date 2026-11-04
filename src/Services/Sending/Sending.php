<?php

namespace SimPay\Laravel\Services\Sending;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sending\Pricing\SendingPricingResponse;
use SimPay\Laravel\Responses\Sending\SendingBalanceResponse;
use SimPay\Laravel\Responses\Sending\SendingOverrideResponse;
use SimPay\Laravel\Services\Service;

final class Sending extends Service
{
    /**
     * @return SendingOverrideResponse[]
     * @throws SimPayException
     */
    public function overrides(): array
    {
        return (new Overrides())->get();
    }

    /**
     * @return SendingBalanceResponse
     * @throws SimPayException
     */
    public function balance(): SendingBalanceResponse
    {
        return (new Balance())->get();
    }

    /**
     * @return SendingPricingResponse
     * @throws SimPayException
     */
    public function pricing(): SendingPricingResponse
    {
        return (new Pricing())->get();
    }

    public function calculator(): Calculator
    {
        return new Calculator();
    }

    public function send(): Send
    {
        return new Send();
    }

    public function history(): History
    {
        return new History();
    }

}
