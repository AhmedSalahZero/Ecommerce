<?php

namespace Tests\Unit\Listeners;

use App\Events\Orders\OrderPaymentFailed;
use App\Listener\Orders\MarkOrderPaymentFailed;
use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MarkOrderPaymentFailedTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_update_the_status_of_the_order_as_payment_failed()
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
        $event = new OrderPaymentFailed($order);
        $listener = new MarkOrderPaymentFailed();
        $listener->handle($event);
        $this->assertEquals(Order::PAYMENT_FAILED , $order->status);


    }
}
