<?php

namespace SimPay\Laravel\Services\Sending;

use SimPay\Laravel\Dto\Sending\CalculatorMassMessageItemData;
use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\Responses\Sending\Calculator\SendingCalculatedMassResponse;
use SimPay\Laravel\Responses\Sending\Calculator\SendingCalculatedMessageResponse;
use SimPay\Laravel\Services\Service;

class Calculator extends Service
{
    /**
     * @param string $content
     * @return SendingCalculatedMessageResponse
     * @throws SimPayException
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function single(string $content): SendingCalculatedMessageResponse
    {
        $response = $this->sendRequest(
            'sms-sending/calculate/single',
            'POST',
            [
                'json' => [
                    'content' => $content,
                ],
            ]
        );

        return SendingCalculatedMessageResponse::from($response->json('data'));
    }

    /**
     * @param CalculatorMassMessageItemData[]|string[] $messages plain strings are treated as content
     * @param bool $detailed
     * @return SendingCalculatedMassResponse
     * @throws SimPayException
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function mass(array $messages, bool $detailed = true): SendingCalculatedMassResponse
    {
        $finalMessages = [];
        foreach ($messages as $message) {
            if ($message instanceof CalculatorMassMessageItemData) {
                $finalMessages[] = $message->toArray();
            } else {
                $finalMessages[] = (new CalculatorMassMessageItemData(content: $message))->toArray();
            }
        }

        $response = $this->sendRequest(
            'sms-sending/calculate/mass',
            'POST',
            [
                'json' => [
                    'messages' => $finalMessages,
                    'detailed' => $detailed,
                ],
            ]
        );

        return SendingCalculatedMassResponse::from($response->json('data'));
    }
}
