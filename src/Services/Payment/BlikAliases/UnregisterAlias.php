<?php
declare(strict_types=1);

namespace SimPay\Laravel\Services\Payment\BlikAliases;

use LogicException;
use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasType;
use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\Services\Service;

class UnregisterAlias extends Service
{
    private BlikAliasType $type;
    private string $uuid;
    private string $value;
    private string $reason;

    /**
     * @throws SimPayException
     * @throws ValidationFailedException
     * @throws AuthorizationException
     * @throws LogicException
     */
    public function execute(): true
    {
        $payload = [];

        if (empty($this->uuid) && empty($this->value) && empty($this->type)) {
            throw new LogicException('You need to provide either uuid or value with type.');
        }

        if (!empty($this->type)) {
            $payload['type'] = $this->type->value;
        }

        if (!empty($this->value)) {
            $payload['value'] = $this->value;
        }

        if (!empty($this->uuid)) {
            $payload['uuid'] = $this->uuid;
        }

        if (!empty($this->reason)) {
            $payload['reason'] = $this->reason;
        }

        $this->sendRequest(
            sprintf('payment/%s/blik/aliases', config('simpay.payment.service_id')),
            'DELETE',
            ['json' => $payload],
        );

        return true;
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

    public function reason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }
}
