<?php

namespace SimPay\Laravel\Responses\Sending\History;

use SimPay\Laravel\Responses\PaginationResponse;

final readonly class MassSendingListResponse
{
    /**
     * @param MassSendingResponse[] $massSendings
     */
    public function __construct(
        public array $massSendings,
        public PaginationResponse $pagination,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            array_map(fn(array $item) => MassSendingResponse::from($item), $json['data']),
            PaginationResponse::from($json['pagination']),
        );
    }
}
