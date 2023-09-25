<?php

namespace Vibraniuum\Pamtechoga\Listeners;

use Vibraniuum\Pamtechoga\Events\OrderUpdated;
use Vibraniuum\Pamtechoga\Events\PaymentUpdated;
use Vibraniuum\Pamtechoga\Models\DeviceToken;
use Vibraniuum\Pamtechoga\Services\PamtechPushNotifications;

class SendPaymentUpdatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PaymentUpdated  $event
     * @return void
     */
    public function handle(PaymentUpdated $event)
    {
        $title = '🎉 Payment status has been updated';
        $body = 'Login to view payment details';

        $devices = DeviceToken::where('organization_id', $event->data['organization_id'])->pluck('device_token');

        resolve(PamtechPushNotifications::class)->sendNotification($title, $body, $devices);
    }
}
