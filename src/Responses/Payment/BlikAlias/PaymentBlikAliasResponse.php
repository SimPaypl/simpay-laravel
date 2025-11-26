<?php
declare(strict_types=1);

namespace SimPay\Laravel\Responses\Payment\BlikAlias;

use Illuminate\Support\Carbon;
use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasStatus;
use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasType;

final readonly class PaymentBlikAliasResponse
{
    public function __construct(
        public string $id,
        public BlikAliasType $type,
        public string $value,
        public string $label,
        public ?string $blikKey,
        public BlikAliasStatus $status,
        public ?Carbon $expiresAt,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            id: $data['id'],
            type: BlikAliasType::from($data['type']),
            value: $data['value'],
            label: $data['label'],
            blikKey: $data['blik_key'],
            status: BlikAliasStatus::from($data['status']),
            expiresAt: $data['expires_at'] ? Carbon::parse($data['expires_at']) : null,
            createdAt: Carbon::parse($data['created_at']),
            updatedAt: Carbon::parse($data['updated_at']),
        );
    }
}