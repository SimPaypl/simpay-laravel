<?php

namespace SimPay\Laravel\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Notifications\SimPayMessage;
use SimPay\Laravel\Tests\TestCase;

class NotificationChannelTest extends TestCase
{
    public function test_string_message_is_sent_with_default_override(): void
    {
        config()->set('simpay.sending.override', 'SKLEP');
        Http::fake(['https://api.simpay.test/sms-sending/send/single' => Http::response(['data' => ['sendingId' => 's-1', 'estimatedCost' => 0.09]])]);

        NotificationFacade::route('simpay', '48123456789')->notify(new SimPayTestNotification('Twój kod: 1234'));

        Http::assertSent(fn (Request $request) => $request->data() === [
            'msisdn' => '48123456789',
            'content' => 'Twój kod: 1234',
            'override' => 'SKLEP',
            'control' => null,
        ]);
    }

    public function test_message_override_and_control_take_precedence(): void
    {
        config()->set('simpay.sending.override', 'SKLEP');
        Http::fake(['*' => Http::response(['data' => ['sendingId' => 's-2', 'estimatedCost' => 0.09]])]);

        NotificationFacade::route('simpay', '48123456789')
            ->notify(new SimPayTestNotification(new SimPayMessage('Hej', override: 'INNY', control: 'c-1')));

        Http::assertSent(fn (Request $request) => $request['override'] === 'INNY' && $request['control'] === 'c-1');
    }

    public function test_missing_recipient_sends_nothing(): void
    {
        Http::fake();

        NotificationFacade::route('simpay', null)->notify(new SimPayTestNotification('Hej'));

        Http::assertNothingSent();
    }

    public function test_missing_override_throws(): void
    {
        Http::fake();

        $this->expectException(SimPayException::class);
        $this->expectExceptionMessage('SimPay sending override is not set');

        (new AnonymousNotifiable())->route('simpay', '48123456789')->notify(new SimPayTestNotification('Hej'));
    }
}

class SimPayTestNotification extends Notification
{
    public function __construct(private readonly string|SimPayMessage $message)
    {
    }

    public function via(object $notifiable): array
    {
        return ['simpay'];
    }

    public function toSimPay(object $notifiable): string|SimPayMessage
    {
        return $this->message;
    }
}
