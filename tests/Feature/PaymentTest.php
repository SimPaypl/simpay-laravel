<?php

namespace SimPay\Laravel\Tests\Feature;

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasStatus;
use SimPay\Laravel\Enums\Payment\BlikAlias\BlikAliasType;
use SimPay\Laravel\Enums\Payment\TransactionStatus;
use SimPay\Laravel\Facades\SimPay;
use SimPay\Laravel\Tests\TestCase;

class PaymentTest extends TestCase
{
    public function test_generate_posts_payload_and_returns_redirect(): void
    {
        Http::fake(['https://api.simpay.test/payment/payment-service/transactions' => Http::response([
            'data' => ['transactionId' => 'tx-1', 'redirectUrl' => 'https://pay.simpay.test/tx-1'],
        ])]);

        $response = SimPay::payment()->generate()
            ->amount(12.5)
            ->currency('EUR')
            ->description('Order 15')
            ->control('order-15')
            ->make();

        $this->assertSame('tx-1', $response->transactionId);
        $this->assertSame('https://pay.simpay.test/tx-1', $response->url);
        Http::assertSent(fn (ClientRequest $request) => $request->method() === 'POST' && $request->data() === [
            'amount' => 12.5,
            'currency' => 'EUR',
            'description' => 'Order 15',
            'control' => 'order-15',
        ]);
    }

    public function test_transaction_info_parses_full_response(): void
    {
        Http::fake(['https://api.simpay.test/payment/payment-service/transactions/tx-1' => Http::response(['data' => [
            'id' => 'tx-1',
            'status' => 'transaction_paid',
            'amount' => ['value' => 12.5, 'currency' => 'PLN', 'commission' => 0.4, 'commission_currency' => 'PLN'],
            'channel' => 'blik',
            'control' => 'order-15',
            'customer' => ['email' => 'jan@example.com'],
            'cart' => [['name' => 'Kawa', 'quantity' => 2, 'price' => 6.25, 'producer' => null, 'category' => null, 'code' => 'K1']],
            'paid_at' => '2026-03-01 12:30:00',
            'expires_at' => null,
            'created_at' => '2026-03-01 12:00:00',
            'updated_at' => '2026-03-01 12:30:00',
            'payer_transaction_id' => 'payer-1',
        ]])]);

        $info = SimPay::payment()->transactionInfo('tx-1');

        $this->assertSame(TransactionStatus::Paid, $info->status);
        $this->assertSame(12.5, $info->amount->value);
        $this->assertSame(0.4, $info->amount->commission);
        $this->assertSame('jan@example.com', $info->customer->email);
        $this->assertNull($info->billing->name);
        $this->assertCount(1, $info->cart);
        $this->assertSame(2, $info->cart[0]->quantity);
        $this->assertSame('K1', $info->cart[0]->code);
        $this->assertSame('2026-03-01 12:30:00', $info->paidAt->format('Y-m-d H:i:s'));
        $this->assertNull($info->expiresAt);
        $this->assertSame('payer-1', $info->payerTransactionId);
    }

    public function test_transaction_info_accepts_response_without_optional_fields(): void
    {
        Http::fake(['*' => Http::response(['data' => [
            'id' => 'tx-2',
            'status' => 'transaction_new',
            'amount' => ['value' => 5, 'currency' => 'PLN', 'commission' => null, 'commission_currency' => null],
            'payer_transaction_id' => 'payer-2',
        ]])]);

        $info = SimPay::payment()->transactionInfo('tx-2');

        $this->assertSame(TransactionStatus::New, $info->status);
        $this->assertSame([], $info->cart);
        $this->assertNull($info->paidAt);
        $this->assertNull($info->createdAt);
    }

    public function test_ipn_signature_is_sha256_of_values_and_service_hash(): void
    {
        $payload = ['id' => 'tx-1', 'amount' => ['value' => '12.50', 'currency' => 'PLN'], 'status' => 'transaction_paid'];
        $valid = hash('sha256', 'tx-1|12.50|PLN|transaction_paid|ipn-secret');

        $this->assertTrue(SimPay::payment()->ipnSignatureValid(Request::create('/', 'POST', $payload + ['signature' => $valid])));
        $this->assertFalse(SimPay::payment()->ipnSignatureValid(Request::create('/', 'POST', $payload + ['signature' => hash('sha256', 'tx-1|12.50|PLN|transaction_paid|other')])));
        $this->assertFalse(SimPay::payment()->ipnSignatureValid(Request::create('/', 'POST', $payload)));
    }

    public function test_blik_aliases_sends_filters_and_parses_pagination(): void
    {
        Http::fake(['*' => Http::response([
            'data' => [[
                'id' => 'alias-1',
                'type' => 'UID',
                'value' => 'user-1',
                'label' => 'Sklep',
                'blik_key' => null,
                'status' => 'alias_active',
                'expires_at' => null,
                'created_at' => '2026-03-01 12:00:00',
                'updated_at' => '2026-03-01 12:00:00',
                'subscription' => null,
            ]],
            'pagination' => ['total' => 21, 'count' => 1, 'per_page' => 20, 'current_page' => 2, 'total_pages' => 2, 'links' => ['prev_page' => 'https://api.simpay.test/prev']],
        ])]);

        $list = SimPay::payment()->blikAliases()->list()->status(BlikAliasStatus::Active)->type(BlikAliasType::UID)->paginate(2);

        Http::assertSent(fn (ClientRequest $request) => str_starts_with($request->url(), 'https://api.simpay.test/payment/payment-service/blik/aliases?')
            && $request->data() === ['page' => 2, 'perPage' => 20, 'filter[status]' => 'alias_active', 'filter[type]' => 'UID']);
        $this->assertCount(1, $list->aliases);
        $this->assertSame(BlikAliasStatus::Active, $list->aliases[0]->status);
        $this->assertNull($list->aliases[0]->subscription);
        $this->assertSame(21, $list->pagination->total);
        $this->assertSame(2, $list->pagination->currentPage);
        $this->assertNull($list->pagination->links->nextPage);
        $this->assertSame('https://api.simpay.test/prev', $list->pagination->links->prevPage);
    }
}
