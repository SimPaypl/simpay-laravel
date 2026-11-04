<?php

namespace SimPay\Laravel\Services\Sending;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sending\SendingBalanceResponse;
use SimPay\Laravel\Services\Service;

class Balance extends Service
{
    /**
     * @return SendingBalanceResponse
     * @throws SimPayException
     */
    public function get(): SendingBalanceResponse
    {
        $response = $this->sendRequest(
            'sms-sending/balance',
            'GET',
        );

        if (!$response->successful()) {
            throw new SimPayException('Could not get sending balance. ' . $response->body());
        }

        return SendingBalanceResponse::from($response->json('data'));
    }
}
