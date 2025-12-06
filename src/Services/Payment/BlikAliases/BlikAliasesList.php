<?php
declare(strict_types=1);

namespace SimPay\Laravel\Services\Payment\BlikAliases;

use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasStatus;
use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasType;
use SimPay\Laravel\Responses\Payment\BlikAlias\PaymentBlikAliasesListResponse;
use SimPay\Laravel\Services\Service;

class BlikAliasesList extends Service
{
    private BlikAliasStatus $status;
    private BlikAliasType $type;
    private string $uuid;
    private string $value;
    private string $sort;

    public function paginate(
        int $page = 1,
        int $perPage = 20,
    ): PaymentBlikAliasesListResponse
    {
        $query = [
            'page' => $page,
            'perPage' => $perPage,
        ];

        if (!empty($this->status)) {
            $query['filter[status]'] = $this->status->value;
        }

        if (!empty($this->type)) {
            $query['filter[type]'] = $this->type->value;
        }

        if (!empty($this->uuid)) {
            $query['filter[uuid]'] = $this->uuid;
        }

        if (!empty($this->value)) {
            $query['filter[value]'] = $this->value;
        }

        if (!empty($this->sort)) {
            $query['sort'] = $this->sort;
        }

        $response = $this->sendRequest(
            sprintf('payment/%s/blik/aliases', config('simpay.payment.service_id')),
            'GET',
            ['query' => $query],
        );

        return PaymentBlikAliasesListResponse::from($response->json());
    }

    public function status(BlikAliasStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function type(BlikAliasType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function uuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function value(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function sort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
