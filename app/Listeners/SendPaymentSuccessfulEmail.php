<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Events\PaymentSuccessful;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessfulMail; // Import the Mailable class

class SendPaymentSuccessfulEmail //implements ShouldQueue
{
   // use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderEvent $event)
    {
        $order = $event->order;

        Mail::to($order->user->email)->send(new PaymentSuccessfulMail($order));
    }
}
