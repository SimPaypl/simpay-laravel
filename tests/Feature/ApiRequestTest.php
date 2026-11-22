<?php

namespace SimPay\Laravel\Tests\Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\ResourceNotFoundException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\Facades\SimPay;
use SimPay\Laravel\SimPay as SimPayClient;
use SimPay\Laravel\Tests\TestCase;

class ApiRequestTest extends TestCase
{
    public function test_request_uses_configured_url_token_and_platform_headers(): void
    {
        Http::fake(['*' => Http::response(['data' => []])]);

        SimPay::sms()->numbers();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.simpay.test/sms/sms-service/numbers?limit=99'
            && $request->hasHeader('Authorization', 'Bearer test-token')
            && $request->hasHeader('X-SIM-PLATFORM', 'PHP-LARAVEL')
            && $request->hasHeader('X-SIM-VERSION', SimPayClient::VERSION));
    }

    public static function errorResponses(): array
    {
        return [
            '401' => [401, AuthorizationException::class, 'Unauthenticated'],
            '403' => [403, AuthorizationException::class, 'Forbidden'],
            '404' => [404, ResourceNotFoundException::class, 'Not found'],
            '500' => [500, SimPayException::class, 'API ERROR 500: Server error'],
        ];
    }

    /**
     * @param class-string<SimPayException> $exceptionClass
     */
    #[DataProvider('errorResponses')]
    public function test_error_status_is_mapped_to_exception(int $status, string $exceptionClass, string $message): void
    {
        Http::fake(['*' => Http::response([
            'message' => str_replace('API ERROR 500: ', '', $message),
            'errorCode' => 'ERR_CODE',
        ], $status)]);

        try {
            SimPay::payment()->transactionInfo('tx-1');
            $this->fail('Expected an exception for HTTP ' . $status);
        } catch (SimPayException $exception) {
            $this->assertSame($exceptionClass, $exception::class);
            $this->assertSame($message, $exception->getMessage());
            $this->assertSame('ERR_CODE', $exception->errorCode);
        }
    }

    public function test_validation_errors_are_exposed(): void
    {
        Http::fake(['*' => Http::response([
            'message' => 'The given data was invalid.',
            'errors' => ['amount' => ['The amount field is required.']],
        ], 422)]);

        try {
            SimPay::payment()->generate()->amount(0)->make();
            $this->fail('Expected ValidationFailedException');
        } catch (ValidationFailedException $exception) {
            $this->assertSame('The given data was invalid.', $exception->getMessage());
            $this->assertSame(['amount' => ['The amount field is required.']], $exception->errors());
        }
    }

    public function test_connection_failure_is_wrapped_in_simpay_exception(): void
    {
        Http::fake(fn () => throw new ConnectionException('Connection timed out'));

        $this->expectException(SimPayException::class);
        $this->expectExceptionMessage('Connection timed out');

        SimPay::sms()->numbers();
    }
}
