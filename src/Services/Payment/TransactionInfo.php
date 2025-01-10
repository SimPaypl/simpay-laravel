<?php

namespace SimPay\Laravel\Services\Payment;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Payment\TransactionInfoResponse;
use SimPay\Laravel\Services\Service;

class TransactionInfo extends Service
{
    public function get(string $transactionId): TransactionInfoResponse
    {
        $transaction = $this->sendRequest(
            sprintf('payment/%s/transactions/%s', config('simpay.payment.service_id'), $transactionId),
            'GET'
        );

        if(!$transaction->successful()) {
            throw new SimPayException('Could not retrieve transaction information: ', $transaction->body());
        }

        return TransactionInfoResponse::from($transaction->json('data'));
    }
}