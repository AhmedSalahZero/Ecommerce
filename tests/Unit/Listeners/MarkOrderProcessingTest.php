<?php

namespace Tests\Unit\Listeners;

use App\Events\Orders\OrderPaid;
use App\Listener\Orders\MarkOrderProcessing;
use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;


class MarkOrderProcessingTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_update_the_status_of_the_order_as_payment_processing()
    {

        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id'=>$user->id
        ]);
        $shippingMethod=factory(ShippingMethod::class)->create();
        $paymentMethod = factory(PaymentMethod::class)->create([
            'user_id'=>$user->id
        ]);
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'address_id'=>$address->id ,
            'shipping_method_id'=>$shippingMethod->id ,
            'status'=>'pending' ,
            'payment_method_id'=>$paymentMethod->id
        ]);
        $event = new OrderPaid($order);
        $listener = new MarkOrderProcessing();
        $listener->handle($event);
        $this->assertEquals(Order::PROCESSING , $order->status);


    }
}
