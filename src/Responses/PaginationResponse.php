<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses;

final readonly class PaginationResponse
{
    public function __construct(
        public int $total,
        public int $count,
        public int $perPage,
        public int $currentPage,
        public int $totalPages,
        public PaginationLinksResponse $links,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            total: $data['total'],
            count: $data['count'],
            perPage: $data['per_page'],
            currentPage: $data['current_page'],
            totalPages: $data['total_pages'],
            links: PaginationLinksResponse::from($data['links']),
        );
    }
}