<?php

namespace SimPay\Laravel\Services\DirectBilling;

use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\DirectBilling\TransactionInfoResponse;
use SimPay\Laravel\Services\Service;

final class TransactionInfo extends Service
{
    /**
     * @throws SimPayException
     */
    public function get(string $transactionId): TransactionInfoResponse
    {
        $transaction = $this->sendRequest(
            sprintf('directbilling/%s/transactions/%s', config('simpay.direct_billing.service_id'), $transactionId),
            'GET'
        );

        if(!$transaction->successful()) {
            throw new SimPayException('Could not retrieve transaction information: ', $transaction->body());
        }

        return TransactionInfoResponse::from($transaction->json('data'));
    }
}