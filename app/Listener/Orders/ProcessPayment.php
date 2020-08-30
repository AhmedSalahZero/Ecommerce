<?php

namespace App\Listener\Orders;

use App\cart\Payments\Gateway;
use App\Events\Orders\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessPayment implements ShouldQueue
{
    protected $gateway ;
    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway ;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order ;
        $this->gateway->withUser($order->user)
            ->getCustomer()
            ->charge($order->paymentMethod , $order->total()->amount());



    }
}
