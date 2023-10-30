<?php

namespace Vibraniuum\Pamtechoga\Listeners;

use Vibraniuum\Pamtechoga\Events\PamtechOrderSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPamtechOrderSubmittedNotification
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
     * @param  PamtechOrderSubmitted  $event
     * @return void
     */
    public function handle(PamtechOrderSubmitted $event)
    {
        Mail::send('emails.orders.submitted', [
            'organization' => $event->order['organization'],
            'product' => $event->order['product'],
            'volume' => $event->order['volume'],
            'organizationEmail' => $event->order['email'],
        ], function($message) use ($event) {
            $message->to('oniicoder@gmail.com');
            $message->from('contact@vibraniuumtech.com', 'New Pamtech OGA Order');
            $message->subject('New Pamtech OGA Order');
        });
    }
}
