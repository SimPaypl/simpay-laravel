<?php

namespace SimPay\Laravel\Services\Payment;

use Illuminate\Http\Request;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Helpers\ArrayHelper;
use SimPay\Laravel\Responses\Payment\ServiceChannel\ChannelResponse;
use SimPay\Laravel\Responses\Payment\TransactionInfoResponse;
use SimPay\Laravel\Services\Service;

final class Payment extends Service
{
    /**
     * @throws SimPayException
     */
    public function generate(): GenerateTransaction
    {
        return (new GenerateTransaction());
    }

    /**
     * @throws SimPayException
     */
    public function transactionInfo(string $transactionId): TransactionInfoResponse
    {
        return (new TransactionInfo())->get($transactionId);
    }

    public function ipnSignatureValid(Request $request): bool
    {
        if (empty($request->input('signature'))) {
            return false;
        }

        $data = ArrayHelper::flatten($request->except('signature'));
        $data[] = config('simpay.payment.hash');

        $signature = hash('sha256', implode('|', $data));

        return hash_equals($signature, $request->input('signature'));
    }

    /**
     * @return ChannelResponse[]
     * @throws SimPayException
     */
    public function channels(): array
    {
        return (new ServiceChannels())->get();
    }
}