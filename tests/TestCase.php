<?php

namespace SimPay\Laravel\Tests;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as Orchestra;
use SimPay\Laravel\SimPayServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    protected function getPackageProviders($app): array
    {
        return [SimPayServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('simpay.bearer_token', 'test-token');
        $app['config']->set('simpay.api_url', 'https://api.simpay.test');
        $app['config']->set('simpay.sms.service_id', 'sms-service');
        $app['config']->set('simpay.direct_billing.service_id', 'db-service');
        $app['config']->set('simpay.payment.service_id', 'payment-service');
        $app['config']->set('simpay.payment.hash', 'ipn-secret');
    }
}
