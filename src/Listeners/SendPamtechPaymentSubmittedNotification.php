<?php

namespace Vibraniuum\Pamtechoga\Listeners;

use Vibraniuum\Pamtechoga\Events\PamtechOrderSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Vibraniuum\Pamtechoga\Events\PamtechPaymentSubmitted;

class SendPamtechPaymentSubmittedNotification
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
     * @param  PamtechPaymentSubmitted  $event
     * @return void
     */
    public function handle(PamtechPaymentSubmitted $event)
    {
        Mail::send('emails.pamtechoga.payment-submitted', [
            'organization' => $event->data['organization'],
            'amount' => number_format($event->data['amount']),
            'staff' => $event->data['staff'],
            'link' => $event->data['link'],
        ], function($message) use ($event) {
            $message->to('oniicoder@gmail.com');
            $message->from('contact@vibraniuumtech.com', 'New Pamtech OGA Payment');
            $message->subject('New Pamtech OGA Payment');
        });
    }
}
