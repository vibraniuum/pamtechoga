<?php

namespace Vibraniuum\Pamtechoga\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Vibraniuum\Pamtechoga\Events\FuelPriceUpdated;
use Vibraniuum\Pamtechoga\Services\DeviceToken;
use Vibraniuum\Pamtechoga\Services\PamtechPushNotifications;

class SendFuelPriceUpdatedNotification
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
     * @param  FuelPriceUpdated  $event
     * @return void
     */
    public function handle(FuelPriceUpdated $event)
    {
        $companyName = $event->formData['company_name'];
        $title = '⛽ '. $companyName .' Updated their fuel prices';
        $body = 'See new fuel market prices today';

        resolve(PamtechPushNotifications::class)->sendNotification($title, $body);
    }
}
