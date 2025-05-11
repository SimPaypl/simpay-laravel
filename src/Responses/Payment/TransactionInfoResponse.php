<?php

namespace SimPay\Laravel\Responses\Payment;

use Illuminate\Support\Carbon;
use SimPay\Laravel\Enums\Payment\TransactionStatus;
use SimPay\Laravel\Responses\Payment\TransactionInfo\AmountResponse;
use SimPay\Laravel\Responses\Payment\TransactionInfo\CartItemResponse;
use SimPay\Laravel\Responses\Payment\TransactionInfo\CustomerFullDataResponse;
use SimPay\Laravel\Responses\Payment\TransactionInfo\CustomerResponse;
use SimPay\Laravel\Responses\Payment\TransactionInfo\RedirectsResponse;

final readonly class TransactionInfoResponse
{
    public function __construct(
        public string                    $id,
        public TransactionStatus         $status,
        public AmountResponse            $amount,
        public ?string                   $channel,
        public ?string                   $control,
        public ?string                   $description,
        public ?RedirectsResponse        $redirects,
        public ?CustomerResponse         $customer,
        public ?CustomerFullDataResponse $billing,
        public ?CustomerFullDataResponse $shipping,
        public array                     $cart = [],
        public ?array                    $additional = [],
        public ?Carbon                   $paidAt = null,
        public ?Carbon                   $expiresAt = null,
        public ?Carbon                   $createdAt = null,
        public ?Carbon                   $updatedAt = null,
        public string                    $payerTransactionId,
    )
    {
    }

    public static function from(array $json)
    {
        $cart = $json['cart'] ?? [];
        if (!is_array($cart)) {
            $cart = [];
        }

        $finalCart = [];
        foreach ($cart as $cartItem) {
            $finalCart[] = CartItemResponse::from($cartItem);
        }

        return new self(
            $json['id'],
            TransactionStatus::from($json['status']),
            AmountResponse::from($json['amount']),
            $json['channel'] ?? null,
            $json['control'] ?? null,
            $json['description'] ?? null,
            RedirectsResponse::from($json['redirect'] ?? null),
            CustomerResponse::from($json['customer'] ?? null),
            CustomerFullDataResponse::from($json['billing'] ?? null),
            CustomerFullDataResponse::from($json['shipping'] ?? null),
            $finalCart,
            $json['additional'] ?? null,
            $json['paid_at'] ? Carbon::parse($json['paid_at']) : null,
            $json['expires_at'] ? Carbon::parse($json['expires_at']) : null,
            $json['created_at'] ? Carbon::parse($json['created_at']) : null,
            $json['updated_at'] ? Carbon::parse($json['updated_at']) : null,
            $json['payer_transaction_id'],
        );
    }
}