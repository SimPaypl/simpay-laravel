<?php

namespace SimPay\Laravel\Services\Sms;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sms\SmsCodeResponse;
use SimPay\Laravel\Services\Service;

class CheckCode extends Service
{
    /**
     * @throws SimPayException
     */
    public function check(string $code, ?int $number = null): SmsCodeResponse
    {
        $payload = [
            'code' => $code,
        ];

        if ($number) {
            $payload['number'] = $number;
        }

        $response = $this->sendRequest(
            sprintf('sms/%s', config('simpay.sms.service_id')),
            'POST',
            ['json' => $payload],
        );

        if (!$response->successful()) {
            throw new SimPayException('Could not check SMS code. ' . $response->body());
        }

        return SmsCodeResponse::from($response->json('data'));
    }
}