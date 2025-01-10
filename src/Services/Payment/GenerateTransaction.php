<?php

namespace SimPay\Laravel\Services\Payment;

use SimPay\Laravel\Dto\Payment\AntiFraudData;
use SimPay\Laravel\Dto\Payment\CartItemData;
use SimPay\Laravel\Dto\Payment\ChannelTypeData;
use SimPay\Laravel\Dto\Payment\CustomerData;
use SimPay\Laravel\Dto\Payment\CustomerFullData;
use SimPay\Laravel\Dto\ReturnData;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\TransactionGeneratedResponse;
use SimPay\Laravel\Services\Service;

final class GenerateTransaction extends Service
{
    private float $amount;
    private string $currency = 'PLN';
    private ?string $description = null;
    private ?string $control = null;
    private CustomerData $customer;
    private AntiFraudData $antiFraud;
    private CustomerFullData $billing;
    private CustomerFullData $shipping;

    private array $cart;
    private ReturnData $returns;
    private string $directChannel;
    private array $channels;
    private ChannelTypeData $channelTypes;
    private ?string $referer = null;

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
            'currency' => $this->currency,
        ];

        if (!empty($this->description)) {
            $payload['description'] = $this->description;
        }

        if (!empty($this->control)) {
            $payload['control'] = $this->control;
        }

        if (!empty($this->customer)) {
            $payload['customer'] = $this->customer->toArray();
        }

        if (!empty($this->antiFraud)) {
            $payload['antifraud'] = $this->antiFraud->toArray();
        }

        if (!empty($this->billing)) {
            $payload['billing'] = $this->billing->toArray();
        }

        if (!empty($this->shipping)) {
            $payload['shipping'] = $this->shipping->toArray();
        }

        if (!empty($this->cart)) {
            $payload['cart'] = $this->cart;
        }

        if (!empty($this->returns)) {
            $payload['returns'] = $this->returns->toArray();
        }

        if (!empty($this->directChannel)) {
            $payload['directChannel'] = $this->directChannel;
        }

        if (!empty($this->channels)) {
            $payload['channels'] = $this->channels;
        }

        if (!empty($this->channelTypes)) {
            $payload['channelTypes'] = $this->channelTypes;
        }

        if (!empty($this->referer)) {
            $payload['referer'] = $this->referer;
        }

        $payment = $this->sendRequest(
            sprintf('payment/%s/transactions', config('simpay.payment.service_id')),
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

    public function currency(string $currency): self
    {
        $this->currency = $currency;

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

    public function customer(CustomerData $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function antiFraud(AntiFraudData $antiFraud): self
    {
        $this->antiFraud = $antiFraud;

        return $this;
    }

    public function billing(CustomerFullData $billing): self
    {
        $this->billing = $billing;

        return $this;
    }

    public function shipping(CustomerFullData $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * @throws SimPayException
     * @var CartItemData[] $items
     */
    public function cart(array $items): self
    {
        foreach ($items as $item) {
            if (!$item instanceof CartItemData) {
                throw new SimPayException(sprintf('%s is not an instance of CartItemData', get_class($item)));
            }

            $this->cart[] = $item->toArray();
        }

        return $this;
    }

    public function returns(\SimPay\Laravel\Dto\ReturnData $returns): self
    {
        $this->returns = $returns;

        return $this;
    }

    public function directChannel(string $directChannel): self
    {
        $this->directChannel = $directChannel;

        return $this;
    }

    public function channels(array $channels): self
    {
        $this->channels = $channels;

        return $this;
    }

    public function channelTypes(ChannelTypeData $channelTypes): self
    {
        $this->channelTypes = $channelTypes;

        return $this;
    }

    public function referer(string $referer): self
    {
        $this->referer = $referer;

        return $this;
    }
}