<?php

namespace App\Listener\Orders;

use App\Events\Orders\OrderPaid;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkOrderProcessing
{

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $event->order->update([
            'status'=>Order::PROCESSING
        ]);

    }

}
