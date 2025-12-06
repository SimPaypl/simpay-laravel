<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\BlikAlias;

use SimPay\Laravel\Responses\PaginationResponse;

final readonly class PaymentBlikAliasesListResponse
{
    public function __construct(
        /** @var PaymentBlikAliasResponse[] $aliases */
        public array              $aliases = [],
        public PaginationResponse $pagination,
    )
    {
    }

    public static function from(array $data): self
    {
        $aliases = [];
        foreach ($data['data'] as $alias) {
            $aliases[] = PaymentBlikAliasResponse::from($alias);
        }

        return new self($aliases, PaginationResponse::from($data['pagination']));
    }
}
