<?php
declare(strict_types=1);

namespace SimPay\Laravel\Notifications;

use Illuminate\Notifications\Notification;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Responses\Sending\Send\SendingQueuedResponse;
use SimPay\Laravel\Services\Sending\Send;

class SimPayChannel
{
    /**
     * Recipient comes from routeNotificationForSimpay() on the notifiable (format 48XXXXXXXXX),
     * message from toSimPay() on the notification (SimPayMessage or plain string content).
     *
     * @throws SimPayException
     */
    public function send(object $notifiable, Notification $notification): ?SendingQueuedResponse
    {
        $msisdn = $notifiable->routeNotificationFor('simpay', $notification);
        if (empty($msisdn)) {
            return null;
        }

        $message = $notification->toSimPay($notifiable);
        if (is_string($message)) {
            $message = new SimPayMessage($message);
        }

        $override = $message->override ?? config('simpay.sending.override');
        if (empty($override)) {
            throw new SimPayException('SimPay sending override is not set. Set SIMPAY_SENDING_OVERRIDE or pass override to SimPayMessage.');
        }

        return (new Send())->single((string)$msisdn, $message->content, $override, $message->control);
    }
}
