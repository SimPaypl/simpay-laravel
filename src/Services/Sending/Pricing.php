<?php

namespace SimPay\Laravel\Services\Sending;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sending\Pricing\SendingPricingResponse;
use SimPay\Laravel\Services\Service;

class Pricing extends Service
{
    /**
     * @return SendingPricingResponse
     * @throws SimPayException
     */
    public function get(): SendingPricingResponse
    {
        $response = $this->sendRequest(
            'sms-sending/pricing',
            'GET',
        );

        if (!$response->successful()) {
            throw new SimPayException('Could not get sending pricing. ' . $response->body());
        }

        return SendingPricingResponse::from($response->json('data'));
    }
}
