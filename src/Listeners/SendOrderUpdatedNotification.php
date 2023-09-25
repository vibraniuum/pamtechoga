<?php

namespace Vibraniuum\Pamtechoga\Listeners;

use Vibraniuum\Pamtechoga\Events\OrderUpdated;
use Vibraniuum\Pamtechoga\Models\DeviceToken;
use Vibraniuum\Pamtechoga\Services\PamtechPushNotifications;

class SendOrderUpdatedNotification
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
     * @param  OrderUpdated  $event
     * @return void
     */
    public function handle(OrderUpdated $event)
    {
        $title = '🎉 An order has been updated';
        $body = 'Login to view order details';

//        $devices = DeviceToken::where('organization_id', $event->data['organization_id'])->pluck('device_token');

        resolve(PamtechPushNotifications::class)->sendNotification($title, $body);
    }
}
