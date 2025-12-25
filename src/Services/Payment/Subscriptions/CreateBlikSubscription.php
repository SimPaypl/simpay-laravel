<?php
declare(strict_types=1);

namespace SimPay\Laravel\Services\Payment\Subscriptions;

use SimPay\Laravel\Dto\Payment\Subscription\BlikAliasData;
use SimPay\Laravel\Dto\Payment\Subscription\BlikSubscriptionOptionData;
use SimPay\Laravel\Dto\Payment\Subscription\BlikTicketData;
use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\Responses\Payment\Subscriptions\BlikSubscriptionCreatedResponse;
use SimPay\Laravel\Responses\TransactionGeneratedResponse;
use SimPay\Laravel\Services\Service;

class CreateBlikSubscription extends Service
{
    private string $transactionId;
    private BlikTicketData $ticket;
    private BlikAliasData $alias;
    private BlikSubscriptionOptionData $options;
    private array $descriptions = [];
    private ?string $appUrl;

    /**
     * @throws SimPayException
     * @throws ValidationFailedException
     * @throws AuthorizationException
     */
    public function make(): BlikSubscriptionCreatedResponse
    {
        $payload = [
            'transactionId' => $this->transactionId,
            'ticket' => $this->ticket->toArray(),
            'alias' => $this->alias->toArray(),
            'options' => $this->options->toArray(),
        ];

        if (!empty($this->descriptions)) {
            $payload['descriptions'] = $this->descriptions;
        }

        if (!empty($this->appUrl)) {
            $payload['appUrl'] = $this->appUrl;
        }

        $subscription = $this->sendRequest(
            sprintf('payment/%s/blik/subscriptions', config('simpay.payment.service_id')),
            'POST',
            ['json' => $payload],
        );

        return new BlikSubscriptionCreatedResponse(
            $subscription->json('data.subscriptionId'),
            $subscription->json('data.aliasId'),
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

    public function ticket(BlikTicketData $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function alias(BlikAliasData $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function options(BlikSubscriptionOptionData $options): self
    {
        $this->options = $options;

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
            $descriptions['line3'] = mb_substr($description, 70, 35);
        }

        $this->descriptions = $descriptions;

        return $this;
    }

    public function appUrl(string $appUrl): self
    {
        $this->appUrl = $appUrl;

        return $this;
    }
}