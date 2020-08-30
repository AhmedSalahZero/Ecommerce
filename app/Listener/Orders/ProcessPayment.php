<?php

namespace App\Listener\Orders;

use App\cart\Payments\Gateway;
use App\Events\Orders\OrderCreated;
use App\Events\Orders\OrderPaid;
use App\Events\Orders\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
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
        try {
            $this->gateway->withUser($order->user)
                ->getCustomer()
                ->charge($order->paymentMethod , $order->total()->amount());

            event(new OrderPaid($order));
        }
        catch (PaymentFailedException $e){
         event(new OrderPaymentFailed($order));
        }


    }
}
