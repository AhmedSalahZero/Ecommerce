<?php

namespace App\Listener\Orders;

use App\Events\Orders\OrderPaymentFailed;
use App\Models\Order;

class MarkOrderPaymentFailed
{

    /**
     * Handle the event.
     *
     * @param  OrderPaymentFailed  $event
     * @return void
     */
    public function handle(OrderPaymentFailed $event)
    {
        $event->order->update([
            'status'=>Order::PAYMENT_FAILED
        ]);

    }
}
