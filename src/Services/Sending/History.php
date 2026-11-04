<?php

namespace SimPay\Laravel\Services\Sending;

use SimPay\Laravel\Enums\Sending\SendingHistoryStatus;
use SimPay\Laravel\Enums\Sending\SendingMassStatus;
use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\ResourceNotFoundException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\Responses\Sending\History\MassSendingListResponse;
use SimPay\Laravel\Responses\Sending\History\MassSendingResponse;
use SimPay\Laravel\Responses\Sending\History\SendingHistoryListResponse;
use SimPay\Laravel\Responses\Sending\History\SendingHistoryResponse;
use SimPay\Laravel\Services\Service;

class History extends Service
{
    /**
     * Single (non-mass) sendings.
     *
     * @param SendingHistoryStatus[] $statuses
     * @throws SimPayException
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function single(int $page = 1, int $perPage = 15, ?string $control = null, ?string $msisdn = null, array $statuses = []): SendingHistoryListResponse
    {
        $response = $this->sendRequest(
            'sms-sending/history/single',
            'GET',
            ['query' => $this->query($page, $perPage, $control, $msisdn, $statuses)],
        );

        return SendingHistoryListResponse::from($response->json());
    }

    /**
     * @throws ResourceNotFoundException
     * @throws SimPayException
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function singleShow(string $sendingId): SendingHistoryResponse
    {
        $response = $this->sendRequest(sprintf('sms-sending/history/single/%s', $sendingId), 'GET');

        return SendingHistoryResponse::from($response->json('data'));
    }

    /**
     * @param SendingMassStatus[] $statuses
     * @throws SimPayException
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function mass(int $page = 1, int $perPage = 15, ?string $control = null, array $statuses = []): MassSendingListResponse
    {
        $response = $this->sendRequest(
            'sms-sending/history/mass',
            'GET',
            ['query' => $this->query($page, $perPage, $control, null, $statuses)],
        );

        return MassSendingListResponse::from($response->json());
    }

    /**
     * Includes statusesCount.
     *
     * @throws ResourceNotFoundException
     * @throws SimPayException
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function massShow(string $massSendingId): MassSendingResponse
    {
        $response = $this->sendRequest(sprintf('sms-sending/history/mass/%s', $massSendingId), 'GET');

        return MassSendingResponse::from($response->json('data'));
    }

    /**
     * Messages of a mass sending.
     *
     * @param SendingHistoryStatus[] $statuses
     * @throws ResourceNotFoundException
     * @throws SimPayException
     * @throws AuthorizationException
     * @throws ValidationFailedException
     */
    public function massMessages(string $massSendingId, int $page = 1, int $perPage = 15, ?string $control = null, ?string $msisdn = null, array $statuses = []): SendingHistoryListResponse
    {
        $response = $this->sendRequest(
            sprintf('sms-sending/history/mass/%s/messages', $massSendingId),
            'GET',
            ['query' => $this->query($page, $perPage, $control, $msisdn, $statuses)],
        );

        return SendingHistoryListResponse::from($response->json());
    }

    /**
     * @param array<SendingHistoryStatus|SendingMassStatus> $statuses
     */
    private function query(int $page, int $perPage, ?string $control, ?string $msisdn, array $statuses): array
    {
        return array_filter([
            'page' => $page,
            'limit' => $perPage,
            'filter[control]' => $control,
            'filter[msisdn]' => $msisdn,
            // spatie query builder splits comma-separated values into an array
            'filter[status]' => implode(',', array_map(fn($status) => $status->value, $statuses)),
        ], fn($value) => $value !== null && $value !== '');
    }
}
