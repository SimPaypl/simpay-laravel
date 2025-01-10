<?php

namespace SimPay\Laravel\Services\Sms;

use SimPay\Laravel\Exceptions\InvalidSmsCodeException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sms\SmsCodeResponse;
use SimPay\Laravel\Responses\Sms\SmsNumberResponse;
use SimPay\Laravel\Services\Service;

final class Sms extends Service
{
    /**
     * @return SmsNumberResponse[]
     * @throws SimPayException
     */
    public function numbers(): array
    {
        return (new ServiceNumbers())->get();
    }

    /**
     * @throws InvalidSmsCodeException
     */
    public function check(string $code, ?int $number = null): SmsCodeResponse
    {
        return (new CheckCode())->check($code, $number);
    }
}