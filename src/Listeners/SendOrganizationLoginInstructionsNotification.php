<?php

namespace Vibraniuum\Pamtechoga\Listeners;

use Vibraniuum\Pamtechoga\Events\OrganizationLoginInstructions;
use Vibraniuum\Pamtechoga\Events\PamtechOrderSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Vibraniuum\Pamtechoga\Events\PamtechPaymentSubmitted;

class SendOrganizationLoginInstructionsNotification
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
     * @param  OrganizationLoginInstructions  $event
     * @return void
     */
    public function handle(OrganizationLoginInstructions $event)
    {
        Mail::send('emails.pamtechoga.login-instructions', [
            'email' => $event->data['email'],
        ], function($message) use ($event) {
            $message->to($event->data['email']);
            $message->from('contact@vibraniuumtech.com', 'Login instructions for Pamtech OGA');
            $message->subject('Login instructions for Pamtech OGA');
        });
    }
}
