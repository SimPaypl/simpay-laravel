<?php

namespace SimPay\Laravel\Services\Payment;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Payment\ServiceCurrency\CurrencyResponse;
use SimPay\Laravel\Services\Service;

class ServiceCurrencies extends Service
{
    /**
     * @return CurrencyResponse[]
     */
    public function get(): array
    {
        $transaction = $this->sendRequest(
            sprintf('payment/%s/currencies', config('simpay.payment.service_id')),
            'GET'
        );

        if(!$transaction->successful()) {
            throw new SimPayException('Could not retrieve service currencies: ', $transaction->body());
        }

        $currencies = [];
        foreach ($transaction->json('data') as $currency) {
            $currencies[] = CurrencyResponse::from($currency);
        }

        return $currencies;
    }
}