<?php

namespace SimPay\Laravel\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use SimPay\Laravel\Facades\SimPay;
use SimPay\Laravel\Tests\TestCase;

class SmsTest extends TestCase
{
    public function test_check_sends_code_with_number_and_parses_response(): void
    {
        Http::fake(['https://api.simpay.test/sms/sms-service' => Http::response(['data' => [
            'used' => 1,
            'code' => 'ABC123',
            'test' => 0,
            'from' => '48123456789',
            'number' => 7136,
            'value' => 1.0,
            'used_at' => '2026-03-01 12:00:00',
        ]])]);

        $result = SimPay::sms()->check('ABC123', 7136);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->data() === ['code' => 'ABC123', 'number' => 7136]);
        $this->assertTrue($result->used);
        $this->assertFalse($result->test);
        $this->assertSame(7136, $result->number);
        $this->assertSame('2026-03-01 12:00:00', $result->usedAt->format('Y-m-d H:i:s'));
    }

    public function test_check_without_number_sends_only_code(): void
    {
        Http::fake(['*' => Http::response(['data' => [
            'used' => false, 'code' => 'ABC123', 'test' => true, 'from' => '48123456789', 'number' => 7136, 'value' => 1.0,
        ]])]);

        $result = SimPay::sms()->check('ABC123');

        Http::assertSent(fn (Request $request) => $request->data() === ['code' => 'ABC123']);
        $this->assertNull($result->usedAt);
    }

    public function test_numbers_are_parsed(): void
    {
        Http::fake(['*' => Http::response(['data' => [
            ['number' => 7136, 'value' => 1.0, 'value_gross' => 1.23, 'adult' => false],
            ['number' => 92550, 'value' => 25.0, 'value_gross' => 30.75, 'adult' => true],
        ]])]);

        $numbers = SimPay::sms()->numbers();

        $this->assertCount(2, $numbers);
        $this->assertSame(92550, $numbers[1]->number);
        $this->assertSame(30.75, $numbers[1]->valueGross);
    }
}
