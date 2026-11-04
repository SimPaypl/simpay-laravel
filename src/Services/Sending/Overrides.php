<?php

namespace SimPay\Laravel\Services\Sending;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sending\SendingOverrideResponse;
use SimPay\Laravel\Services\Service;

class Overrides extends Service
{
    /**
     * @return SendingOverrideResponse[]
     * @throws SimPayException
     */
    public function get(): array
    {
        $response = $this->sendRequest(
            'sms-sending/overrides',
            'GET',
        );

        if (!$response->successful()) {
            throw new SimPayException('Could not get sending overrides. ' . $response->body());
        }

        $overrides = [];
        foreach ($response->json('data') as $override) {
            $overrides[] = SendingOverrideResponse::from($override);
        }

        return $overrides;
    }
}
