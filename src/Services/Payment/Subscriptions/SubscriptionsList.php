<?php
declare(strict_types=1);

namespace SimPay\Laravel\Services\Payment\Subscriptions;

use SimPay\Laravel\Enums\Payment\Subscription\SubscriptionMode;
use SimPay\Laravel\Enums\Payment\Subscription\SubscriptionStatus;
use SimPay\Laravel\Responses\Payment\Subscriptions\PaymentSubscriptionsListResponse;
use SimPay\Laravel\Services\Service;

class SubscriptionsList extends Service
{
    private string $uuid;
    private SubscriptionStatus $status;
    private SubscriptionMode $mode;
    private string $sort;

    public function paginate(
        int $page = 1,
        int $perPage = 20,
    ): PaymentSubscriptionsListResponse
    {
        $query = [
            'page' => $page,
            'perPage' => $perPage,
        ];

        if (!empty($this->uuid)) {
            $query['filter[uuid]'] = $this->uuid;
        }

        if (!empty($this->status)) {
            $query['filter[status]'] = $this->status->value;
        }

        if (!empty($this->mode)) {
            $query['filter[mode]'] = $this->mode->value;
        }

        if (!empty($this->sort)) {
            $query['sort'] = $this->sort;
        }

        $response = $this->sendRequest(
            sprintf('payment/%s/subscriptions', config('simpay.payment.service_id')),
            'GET',
            ['query' => $query],
        );

        return PaymentSubscriptionsListResponse::from($response->json());
    }

    public function uuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function status(SubscriptionStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function mode(SubscriptionMode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function sort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}