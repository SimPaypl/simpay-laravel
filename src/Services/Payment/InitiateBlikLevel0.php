<?php

namespace SimPay\Laravel\Services\Payment;

use SimPay\Laravel\Enums\Payment\BlikLevel0TicketType;
use SimPay\Laravel\Exceptions\BlikLevel0\InvalidBlikTicketException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\TransactionGeneratedResponse;
use SimPay\Laravel\Services\Service;

final class InitiateBlikLevel0 extends Service
{
    private BlikLevel0TicketType $ticketType;
    private string $ticket;
    private string $transactionId;

    /**
     * @throws SimPayException
     */
    public function make(): true
    {
        $payload = [
            'ticket' => [
                $this->ticketType->value => $this->ticket,
            ],
        ];

        $level0 = $this->sendRequest(
            sprintf('payment/%s/blik/level0/%s', config('simpay.payment.service_id'), $this->transactionId),
            'POST',
            ['json' => $payload],
            checkHeaders: false,
        );

        if ($level0->successful()) {
            return true;
        }

        if (!$level0->badRequest()) {
            throw new SimPayException(
                message: sprintf('[%s] %s', $level0->json('errorCode', $level0->status()), $level0->json('message')),
                errorCode: $level0->json('errorCode'),
            );
        }

        $errorCode = $level0->json('errorCode');

        if (in_array($errorCode, [
            'INVALID_BLIK_CODE',
            'INVALID_BLIK_CODE_FORMAT',
            'BLIK_CODE_EXPIRED',
            'BLIK_CODE_CANCELLED',
            'BLIK_CODE_USED',
            'BLIK_CODE_NOT_SUPPORTED',
        ])) {
            throw new InvalidBlikTicketException(
                message: sprintf('[%s] %s', $errorCode, $level0->json('message')),
                errorCode: $errorCode,
            );
        }

        throw new SimPayException(
            message: sprintf('[%s] %s', $errorCode, $level0->json('message')),
            errorCode: $errorCode,
        );
    }

    public function ticket(string $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function ticketType(BlikLevel0TicketType $ticketType): self
    {
        $this->ticketType = $ticketType;

        return $this;
    }

    public function transaction(TransactionGeneratedResponse|string $transaction): self
    {
        if ($transaction instanceof TransactionGeneratedResponse) {
            $this->transactionId = $transaction->transactionId;

            return $this;
        }

        $this->transactionId = $transaction;

        return $this;
    }
}