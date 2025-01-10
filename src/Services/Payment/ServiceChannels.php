<?php

namespace SimPay\Laravel\Services\Payment;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Payment\ServiceChannel\ChannelResponse;
use SimPay\Laravel\Responses\Payment\TransactionInfoResponse;
use SimPay\Laravel\Services\Service;

class ServiceChannels extends Service
{
    /**
     * @return ChannelResponse[]
     */
    public function get(): array
    {
        $transaction = $this->sendRequest(
            sprintf('payment/%s/channels', config('simpay.payment.service_id')),
            'GET'
        );

        if(!$transaction->successful()) {
            throw new SimPayException('Could not retrieve transaction information: ', $transaction->body());
        }

        $channels = [];
        foreach ($transaction->json('data') as $channel) {
            $channels[] = ChannelResponse::from($channel);
        }

        return $channels;
    }
}