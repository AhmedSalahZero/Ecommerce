<?php

namespace Tests\Unit\Listeners;


use App\Events\Orders\OrderPaid;
use App\Listener\Orders\CreateTransaction;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Tests\TestCase;

class CreateTransactionListenerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_creates_a_transaction()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'subtotal'=>1000 ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
        ]);

        $listener = new CreateTransaction();
        $event = new OrderPaid($order);
        $listener->handle($event);
        $this->assertDatabaseHas('Transactions' , [
            'order_id'=>$order->id  ,
            'user_id'=>$order->user->id ,
            'amount'=>$order->total()->amount()

        ]);

    }
}
