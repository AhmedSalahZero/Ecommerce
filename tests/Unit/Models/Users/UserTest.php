<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_hashes_password_when_creating()
    {
       $user = factory(User::class)->create([
           'password'=>'123456'
       ]);
       $this->assertNotEquals($user->password , '123456');

    }
    public function test_it_has_many_cart_productions(){
        $user = factory(User::class)->create();
        $productVariation = factory(ProductVariation::class)->create();
        $user->cart()->attach($productVariation);
        $this->assertInstanceOf(ProductVariation::class , $user->cart()->first());

    }
    public function test_it_has_a_quantity_for_each_product(){
        $user = factory(User::class)->create();
        $productVariation = factory(ProductVariation::class)->create();
        $user->cart()->attach($productVariation , ['quantity'=>'5']); // the second argument to modify on pivot attribute [quantity]
        $this->assertEquals(5 , $user->cart()->first()->pivot->quantity);


    }
    public function test_it_has_many_address()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->make();
        $user->addresses()->save($address);
        $this->assertInstanceOf(Address::class , $user->addresses->first());


    }

    public function test_it_has_many_orders()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id

        ]);
        $user->orders()->save($order);
        $this->assertInstanceOf(Order::class , $user->orders->first());

    }
    public function test_it_has_many_payment_methods()
    {
        $user = factory(User::class)->create();
        $paymentMethod = factory(PaymentMethod::class)->create(['user_id' => $user->id]);
        $this->assertInstanceOf(PaymentMethod::class , $user->paymentMethods->first());


    }
    public function test_it_has_many_transactions()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'subtotal'=>1000 ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
        ]);

        $user->transactions()->create([
            'order_id'=>$order->id ,
            'user_id'=>$user->id ,
            'amount'=>5  ,

        ]);
        $this->assertInstanceOf(Transaction::class , $user->Transactions->first());

    }

}
