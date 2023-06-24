<?php

namespace Vibraniuum\Pamtechoga\Listeners;

use Vibraniuum\Pamtechoga\Events\AnnouncementBlasted;
use Vibraniuum\Pamtechoga\Services\PamtechPushNotifications;

class SendAnnouncementBlastedNotification
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
     * @param  AnnouncementBlasted  $event
     * @return void
     */
    public function handle(AnnouncementBlasted $event)
    {
        $title = $event->formData['title'];
        $body = $event->formData['message'];

        resolve(PamtechPushNotifications::class)->sendNotification($title, $body);
    }
}
