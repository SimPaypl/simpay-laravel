<?php
declare(strict_types=1);

namespace SimPay\Laravel\Services\Payment\Subscriptions;

use SimPay\Laravel\Dto\Payment\Subscription\BlikAutoPayment\BlikAutoPaymentAliasData;
use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\Responses\Payment\Subscriptions\BlikAutoPaymentSubscriptionExecutedResponse;
use SimPay\Laravel\Responses\TransactionGeneratedResponse;
use SimPay\Laravel\Services\Service;

class BlikAutoPayment extends Service
{
    private string $transactionId;
    private int $attempt;
    private BlikAutoPaymentAliasData $alias;
    private array $descriptions = [];

    public function __construct(
        private readonly string $subscriptionId,
    )
    {
    }

    /**
     * @throws SimPayException
     * @throws ValidationFailedException
     * @throws AuthorizationException
     */
    public function make(): BlikAutoPaymentSubscriptionExecutedResponse
    {
        $payload = [
            'transactionId' => $this->transactionId,
        ];

        if (!empty($this->descriptions)) {
            $payload['descriptions'] = $this->descriptions;
        }

        if (!empty($this->attempt)) {
            $payload['attempt'] = $this->attempt;
        }

        if (!empty($this->alias)) {
            $payload['alias'] = $this->alias->toArray();
        }

        $subscription = $this->sendRequest(
            sprintf('payment/%s/blik/subscriptions/%s/autopayment', config('simpay.payment.service_id'), $this->subscriptionId),
            'POST',
            ['json' => $payload],
        );

        return new BlikAutoPaymentSubscriptionExecutedResponse(
            (bool)$subscription->json('data.needsUserConfirmation'),
        );
    }

    public function transaction(TransactionGeneratedResponse|string $transaction): self
    {
        if ($transaction instanceof TransactionGeneratedResponse) {
            $transaction = $transaction->transactionId;
        }

        $this->transactionId = $transaction;

        return $this;
    }

    public function attempt(int $attempt): self
    {
        $this->attempt = $attempt;

        return $this;
    }

    public function alias(BlikAutoPaymentAliasData $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function description(string $description): self
    {
        $descriptions = [
            'line1' => mb_substr($description, 0, 35),
        ];
        if (mb_strlen($description) > 35) {
            $descriptions['line2'] = mb_substr($description, 35, 35);
        }
        if (mb_strlen($description) > 70) {
            $descriptions['line3'] = mb_substr($description, 35, 70);
        }

        $this->descriptions = $descriptions;

        return $this;
    }
}
