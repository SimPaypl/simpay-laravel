<?php

namespace SimPay\Laravel\Tests\Feature;

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimPay\Laravel\Enums\DirectBilling\AmountType;
use SimPay\Laravel\Enums\DirectBilling\MobileCarrier;
use SimPay\Laravel\Enums\DirectBilling\TransactionStatus;
use SimPay\Laravel\Facades\SimPay;
use SimPay\Laravel\Tests\TestCase;

class DirectBillingTest extends TestCase
{
    public function test_transaction_info_parses_full_response(): void
    {
        Http::fake(['https://api.simpay.test/directbilling/db-service/transactions/db-1' => Http::response(['data' => [
            'id' => 'db-1',
            'status' => 'transaction_db_payed',
            'phoneNumber' => '48123456789',
            'control' => 'order-7',
            'value' => 12.3,
            'value_net' => 10.0,
            'value_created' => 10.0,
            'value_created_type' => 'NET',
            'operator' => 'orange',
            'notify' => ['is_send' => true, 'last_send_at' => '2026-03-01 12:31:00', 'count' => 2],
            'score' => 0.5,
            'created_at' => '2026-03-01 12:00:00',
            'updated_at' => '2026-03-01 12:31:00',
        ]])]);

        $info = SimPay::directBilling()->transactionInfo('db-1');

        $this->assertSame(TransactionStatus::Paid, $info->status);
        $this->assertSame('48123456789', $info->phoneNumber);
        $this->assertSame(10.0, $info->valueNet);
        $this->assertSame(AmountType::Net, $info->valueCreatedType);
        $this->assertSame(MobileCarrier::Orange, $info->carrier);
        $this->assertTrue($info->notify->isSend);
        $this->assertSame(2, $info->notify->count);
        $this->assertSame('2026-03-01 12:00:00', $info->createdAt->format('Y-m-d H:i:s'));
    }

    public function test_transaction_info_accepts_response_without_optional_fields(): void
    {
        Http::fake(['*' => Http::response(['data' => [
            'id' => 'db-2',
            'status' => 'transaction_db_new',
            'value_created_type' => null,
            'notify' => ['is_send' => false],
        ]])]);

        $info = SimPay::directBilling()->transactionInfo('db-2');

        $this->assertSame(TransactionStatus::New, $info->status);
        $this->assertNull($info->phoneNumber);
        $this->assertSame(0.0, $info->value);
        $this->assertNull($info->valueCreatedType);
        $this->assertNull($info->carrier);
        $this->assertNull($info->createdAt);
        $this->assertNull($info->notify->lastSendAt);
    }

    public function test_calculate_sends_amount_and_parses_carriers(): void
    {
        Http::fake(['*' => Http::response(['data' => [
            'orange' => ['net' => 10.0, 'gross' => 12.3],
            't-mobile' => ['net' => 9.5, 'gross' => 11.69],
        ]])]);

        $result = SimPay::directBilling()->calculate(12.3);

        Http::assertSent(fn (ClientRequest $request) => $request->url() === 'https://api.simpay.test/directbilling/db-service/calculate?amount=12.3');
        $this->assertSame(12.3, $result->orange->gross);
        $this->assertSame(9.5, $result->tMobile->net);
        $this->assertNull($result->play);
        $this->assertNull($result->plus);
        $this->assertNull($result->netia);
    }

    public function test_ipn_signature_uses_direct_billing_hash(): void
    {
        config()->set('simpay.direct_billing.hash', 'db-secret');
        $payload = ['id' => 'db-1', 'status' => 'transaction_db_payed', 'values' => ['net' => '10.00', 'gross' => '12.30']];

        $valid = hash('sha256', 'db-1|transaction_db_payed|10.00|12.30|db-secret');
        $signedWithPaymentHash = hash('sha256', 'db-1|transaction_db_payed|10.00|12.30|ipn-secret');

        $this->assertTrue(SimPay::directBilling()->ipnSignatureValid(Request::create('/', 'POST', $payload + ['signature' => $valid])));
        $this->assertFalse(SimPay::directBilling()->ipnSignatureValid(Request::create('/', 'POST', $payload + ['signature' => $signedWithPaymentHash])));
    }
}
