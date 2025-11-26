<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses;

final readonly class PaginationLinksResponse
{
    public function __construct(
        public ?string $nextPage = null,
        public ?string $prevPage = null,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            nextPage: $data['next_page'] ?? null,
            prevPage: $data['prev_page'] ?? null,
        );
    }
}