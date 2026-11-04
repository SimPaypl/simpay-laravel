<?php

namespace SimPay\Laravel\Services\Sending;

use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\Responses\Sending\Send\MassSendingQueuedResponse;
use SimPay\Laravel\Responses\Sending\Send\SendingQueuedResponse;
use SimPay\Laravel\Services\Service;

class Send extends Service
{
    /**
     * @param string $msisdn recipient in 48XXXXXXXXX format
     * @param string $override approved sender name
     * @throws SimPayException errorCode: OVERRIDE_NOT_FOUND, INVALID_CONTENT, PRICING_NOT_FOUND, ...
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function single(string $msisdn, string $content, string $override, ?string $control = null): SendingQueuedResponse
    {
        $response = $this->sendRequest(
            'sms-sending/send/single',
            'POST',
            [
                'json' => [
                    'msisdn' => $msisdn,
                    'content' => $content,
                    'override' => $override,
                    'control' => $control,
                ],
            ]
        );

        return SendingQueuedResponse::from($response->json('data'));
    }

    /**
     * @param array<int, array{msisdn: string, content: string}> $messages max 200, msisdn must be distinct
     * @param string $override approved sender name
     * @throws SimPayException errorCode: OVERRIDE_NOT_FOUND, INVALID_CONTENT, PRICING_NOT_FOUND, ...
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function mass(array $messages, string $override, ?string $name = null, ?string $control = null): MassSendingQueuedResponse
    {
        $response = $this->sendRequest(
            'sms-sending/send/mass',
            'POST',
            [
                'json' => [
                    'messages' => array_values($messages),
                    'override' => $override,
                    'name' => $name,
                    'control' => $control,
                ],
            ]
        );

        return MassSendingQueuedResponse::from($response->json('data'));
    }
}
