<?php

namespace SimPay\Laravel\Services\DirectBilling;

use SimPay\Laravel\Dto\ReturnData;
use SimPay\Laravel\Enums\DirectBilling\AmountType;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\TransactionGeneratedResponse;
use SimPay\Laravel\Services\Service;

final class GenerateTransaction extends Service
{
    private float $amount;
    private AmountType $amountType = AmountType::Gross;
    private ?string $description = null;
    private ?string $control = null;
    private ReturnData $returns;
    private string $phoneNumber;
    private string $steamId;
    private string $email;

    /**
     * @throws SimPayException
     */
    public function make(): TransactionGeneratedResponse
    {
        if (empty($this->amount)) {
            throw new SimPayException('You have to set amount before generating transaction');
        }

        $payload = [
            'amount' => $this->amount,
            'amountType' => $this->amountType->value,
        ];

        if (!empty($this->description)) {
            $payload['description'] = $this->description;
        }

        if (!empty($this->control)) {
            $payload['control'] = $this->control;
        }

        if (!empty($this->returns)) {
            $payload['returns'] = $this->returns->toArray();
        }

        if (!empty($this->phoneNumber)) {
            $payload['phoneNumber'] = $this->phoneNumber;
        }

        if (!empty($this->steamId)) {
            $payload['steamid'] = $this->steamId;
        }

        if (!empty($this->email)) {
            $payload['email'] = $this->email;
        }

        $payment = $this->sendRequest(
            sprintf('directbilling/%s/transactions', config('simpay.direct_billing.service_id')),
            'POST',
            ['json' => $payload],
        );

        if(!$payment->successful()) {
            throw new SimPayException('Could not generate transaction: ' . $payment->body());
        }

        return new TransactionGeneratedResponse(
            $payment->json('data.redirectUrl'),
            $payment->json('data.transactionId'),
        );
    }

    public function amount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function amountType(AmountType $amountType): self
    {
        $this->amountType = $amountType;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function control(string $control): self
    {
        $this->control = $control;

        return $this;
    }

    public function returns(ReturnData $returns): self
    {
        $this->returns = $returns;

        return $this;
    }

    public function phoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function steamId(string $steamId): self
    {
        $this->steamId = $steamId;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}