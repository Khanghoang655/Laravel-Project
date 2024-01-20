<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Models\FootballMatch;
use App\Models\OrderItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MinusQtySeat
{
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
    public function handle(OrderEvent $event): void
    {
        $order = $event->order;
        foreach($order->order_items as $item){
            $match = FootballMatch::find($item->match_id);
            $qty = ($match->seat - $item->qty) < 0 ? 0 : ($match->seat - $item->qty);

            $match->seats_remaining = $qty;

            $match->save();

        }
    }
}
