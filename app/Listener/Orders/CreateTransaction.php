<?php

namespace App\Listener\Orders;

use App\Events\Orders\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTransaction
{

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
     $order = $event->order ;
        $event->order->transactions()->create([
           'order_id'=>$order->id  ,
           'user_id'=>$order->user->id ,
           'amount'=>$order->total()->amount()
        ]);
    }
}
