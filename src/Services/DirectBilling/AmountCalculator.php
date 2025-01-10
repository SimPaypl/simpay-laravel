<?php

namespace SimPay\Laravel\Services\DirectBilling;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\DirectBilling\AmountCalculatorResponse;
use SimPay\Laravel\Services\Service;

final class AmountCalculator extends Service
{
    /**
     * @throws SimPayException
     */
    public function get(float $amount): AmountCalculatorResponse
    {
        $request = $this->sendRequest(
            sprintf('directbilling/%s/calculate', config('simpay.direct_billing.service_id')),
            'GET',
            ['query' => ['amount' => $amount]]
        );

        if (!$request->successful()) {
            throw new SimPayException('Could not retrieve amount calculation: ', $request->body());
        }

        return AmountCalculatorResponse::from($request->json('data'));
    }
}