<?php

namespace SimPay\Laravel\Responses\Sending\History;

use SimPay\Laravel\Responses\PaginationResponse;

final readonly class SendingHistoryListResponse
{
    /**
     * @param SendingHistoryResponse[] $messages
     */
    public function __construct(
        public array $messages,
        public PaginationResponse $pagination,
    )
    {
    }

    public static function from(array $json): self
    {
        return new self(
            array_map(fn(array $item) => SendingHistoryResponse::from($item), $json['data']),
            PaginationResponse::from($json['pagination']),
        );
    }
}
