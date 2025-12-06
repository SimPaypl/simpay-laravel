<?php

namespace SimPay\Laravel\Dto\Payment\Subscription;

use Illuminate\Support\Carbon;
use SimPay\Laravel\Enums\Payment\Subscription\BlikModel;
use SimPay\Laravel\Exceptions\SimPayException;

final class BlikSubscriptionOptionData
{
    /**
     * @throws SimPayException
     */
    public function __construct(
        private BlikModel          $model,
        private string|Carbon|null $expiresAt = null,
        private ?string            $frequency = null,
        private ?float             $amountLimitPerTransaction = null,
        private string|Carbon|null $initiationDate = null,
        private ?float             $amountLimitTotal = null,
        private ?int               $transactionsCountLimit = null,
    )
    {
        if($this->expiresAt instanceof Carbon){
            $this->expiresAt = $this->expiresAt->format('Y-m-d');
        }

        if($this->initiationDate instanceof Carbon){
            $this->initiationDate = $this->initiationDate->format('Y-m-d');
        }

        if ($this->model === BlikModel::A) {
            if (
                empty($this->expiresAt) ||
                empty($this->frequency) ||
                empty($this->amountLimitPerTransaction) ||
                empty($this->initiationDate) ||
                empty($this->amountLimitTotal)
            ) {
                throw new SimPayException('When using model A, you need to send expiresAt, frequency, amountLimitPerTransaction, initiationDate and amountLimitTotal');
            }
        } else if ($this->model === BlikModel::O) {
            if (
                !empty($this->frequency) ||
                !empty($this->amountLimitPerTransaction) ||
                !empty($this->amountLimitTotal) ||
                !empty($this->transactionsCountLimit)
            ) {
                throw new SimPayException('When using model O, you cannot set frequency, amountLimitPerTransaction, amountLimitTotal or transactionsCountLimit.');
            }
        } else if ($this->model === BlikModel::M) {
            if (
                !empty($this->amountLimitPerTransaction) ||
                !empty($this->amountLimitTotal) ||
                !empty($this->transactionsCountLimit)
            ) {
                throw new SimPayException('When using model M, you cannot set amountLimitPerTransaction, amountLimitTotal or transactionsCountLimit.');
            }
        }
    }

    public function toArray(): array
    {
        return array_filter([
            'model' => $this->model->value,
            'expiresAt' => $this->expiresAt,
            'frequency' => $this->frequency,
            'amountLimitPerTransaction' => $this->amountLimitPerTransaction,
            'initiationDate' => $this->initiationDate,
            'amountLimitTotal' => $this->amountLimitTotal,
            'transactionsCountLimit' => $this->transactionsCountLimit,
        ]);
    }
}