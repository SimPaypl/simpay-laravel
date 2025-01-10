<?php

namespace SimPay\Laravel\Services\DirectBilling;

use Illuminate\Http\Request;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Helpers\ArrayHelper;
use SimPay\Laravel\Responses\DirectBilling\AmountCalculatorResponse;
use SimPay\Laravel\Responses\DirectBilling\TransactionInfoResponse;
use SimPay\Laravel\Services\Service;

final class DirectBilling extends Service
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

    public function calculate(float $amount): AmountCalculatorResponse
    {
        return (new AmountCalculator())->get($amount);
    }

    public function ipnSignatureValid(Request $request): bool
    {
        if (empty($request->input('signature'))) {
            return false;
        }

        $data = ArrayHelper::flatten($request->except('signature'));
        $data[] = config('simpay.direct_billing.hash');

        $signature = hash('sha256', implode('|', $data));

        return hash_equals($signature, $request->input('signature'));
    }
}