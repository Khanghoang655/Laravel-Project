<?php

namespace App\Listeners;

use App\Events\SendVerificationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;

class SendVerificationEmailListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendVerificationEmail  $event
     * @return void
     */
    public function handle(SendVerificationEmail $event)
    {
        Mail::to($event->user->email)->send(new VerificationEmail($event->user));
    }
}
