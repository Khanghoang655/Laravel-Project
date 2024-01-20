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
        // $orders = Order::where('user_id', Auth::user()->id)->with('order_items')->get();
        // $orderItems = [];
        // dd($this->order);


        // Lấy các id của các đơn đặt hàng từ $this->order
        $orderIds = $this->order->pluck('id')->toArray();

        // Lấy các OrderItem liên quan đến các đơn đặt hàng
        $orderItems = OrderItem::whereIn('order_id', $orderIds)->get();

        // Lấy các id của các trận đấu từ các OrderItem
        $matchIds = $orderItems->pluck('match_id')->toArray();

        // Lấy các trận đấu liên quan đến các id đã lấy
        $matches = FootballMatch::whereIn('id', $matchIds)->get();

        return $this->view('mail.paymentSuccessfulMail', ['order' => $this->order, 'match' => $matches]);
    }
}