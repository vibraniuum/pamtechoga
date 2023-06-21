<?php

namespace Vibraniuum\Pamtechoga\Listeners;

use Vibraniuum\Pamtechoga\Events\OrderUpdated;
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
        $title = '🎉 Your order has been updated';
        $body = 'Login to view your current order status';

        resolve(PamtechPushNotifications::class)->sendNotification($title, $body);
    }
}
