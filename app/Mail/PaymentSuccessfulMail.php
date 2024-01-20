<?php

namespace App\Mail;

use App\Models\FootballMatch;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PaymentSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Successful Mail',
        );
    }
    public function build()
    {
        $orders = Order::where('user_id', Auth::user()->id)->with('order_items')->get();
        $orderItems = [];

        foreach ($orders as $order) {
            foreach ($order->order_items as $orderItem) {
                $orderItems[] = $orderItem->match_id;
            }
        }

        $match = FootballMatch::whereIn('id', $orderItems)->get();

        $orderItem = OrderItem::whereIn('order_id', $orders->pluck('id'))->get();
        return $this->view('mail.paymentSuccessfulMail', ['order' => $this->order, 'match' => $match, 'orderItem' => $orderItem]);
    }
}