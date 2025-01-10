<?php

namespace SimPay\Laravel\Services\Sms;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sms\SmsNumberResponse;
use SimPay\Laravel\Services\Service;

class ServiceNumbers extends Service
{
    /**
     * @return SmsNumberResponse[]
     * @throws SimPayException
     */
    public function get(): array
    {
        $response = $this->sendRequest(
            sprintf('sms/%s/numbers', config('simpay.sms.service_id')),
            'GET',
            ['query' => ['limit' => 99]],
        );

        if (!$response->successful()) {
            throw new SimPayException('Could not get Service numbers. ' . $response->body());
        }

        $numbers = [];
        foreach ($response->json('data') as $number) {
            $numbers[] = SmsNumberResponse::from($number);
        }

        return $numbers;
    }
}