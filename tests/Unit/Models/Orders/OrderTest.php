<?php

namespace Tests\Unit\Models\Orders;

use App\cart\Cart;
use App\cart\Money;
use App\Events\Orders\OrderCreated;
use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase ;

    public function test_it_has_a_default_Status_of_pending()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id

        ]);
        $this->assertEquals('pending' , $order->status);


    }
    public function test_it_belongs_to_user(){
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'address_id'=>factory(Address::class)->create([
                'user_id'=>$user->id
            ])->id ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id,
            'shipping_method_id'=>factory(ShippingMethod::class)->create()->id
        ]);
        $this->assertInstanceOf(User::class, $order->user);
    }

    public function test_it_belongs_to_address(){
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'address_id'=>factory(Address::class)->create([
                'user_id'=>$user->id
            ])->id ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id,
            'shipping_method_id'=>factory(ShippingMethod::class)->create()->id
        ]);
        $this->assertInstanceOf(Address::class, $order->address);

    }
    public function test_it_belongs_to_shipping_method()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
            ,
            'address_id'=>factory(Address::class)->create([
                'user_id'=>$user->id
            ])->id ,
            'shipping_method_id'=>factory(ShippingMethod::class)->create()->id
        ]);
        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }



    public function test_it_can_create_an_order()
    {
        $user = factory(User::class)->create([
            'id'=>1000
        ]);
        $product = factory(ProductVariation::class)->create();
        $user->cart()->save($product);
        $country = factory(Country::class)->create();
        $shipping_method = factory(ShippingMethod::class)->create();
        $address=factory(Address::class)->create(['user_id'=>$user->id , 'country_id'=>$country->id]);

        $country->shippingMethods()->save($shipping_method);

        $this->jsonAs($user , 'post', 'api/orders' , [
            'user_id'=>$user->id ,
            'address_id'=>$address->id ,
            'shipping_method_id'=>$shipping_method->id,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id



        ]);
        $this->assertDatabaseHas('orders' , [
            'user_id'=>$user->id ,
            'address_id'=>$address->id ,
            'shipping_method_id'=>$shipping_method->id,


        ]);

    }
    public function test_it_has_many_productss()
    {
        $user =factory(User::class)->create();
        $country = factory(Country::class)->create();
        $address= factory(Address::class)->create([
            'user_id'=>$user->id ,
            'country_id'=>$country->id ,
        ]);
        $Shipping_method = factory(ShippingMethod::class)->create();
        $country->shippingMethods()->save($Shipping_method);
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'address_id'=>$address->id ,
            'shipping_method_id'=>$Shipping_method->id,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id

        ]);
        $product = factory(ProductVariation::class)->create();
        $order->products()->save($product , [
            'quantity'=>8
        ]);
        $this->assertInstanceOf(ProductVariation::class , $order->products->first());


    }
    public function test_it_has_The_quantity()
    {
        $user =factory(User::class)->create();
        $country = factory(Country::class)->create();
        $address= factory(Address::class)->create([
            'user_id'=>$user->id ,
            'country_id'=>$country->id ,
        ]);
        $Shipping_method = factory(ShippingMethod::class)->create();
        $country->shippingMethods()->save($Shipping_method);
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'address_id'=>$address->id ,
            'shipping_method_id'=>$Shipping_method->id,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id

        ]);
        $product = factory(ProductVariation::class)->create();
        $order->products()->save($product , [
            'quantity'=>8
        ]);
        $this->assertEquals(8 , $order->products->first()->pivot->quantity);
    }
    public function test_it_fires_an_created_order_event_when_order_is_done()
    {
        Event::fake();

        $user = factory(User::class)->create([
            'id'=>1000
        ]);
       $user->cart()->save(factory(ProductVariation::class)->create());
        $country = factory(Country::class)->create();
        $shipping_method = factory(ShippingMethod::class)->create();
        $address=factory(Address::class)->create(['user_id'=>$user->id , 'country_id'=>$country->id]);

        $country->shippingMethods()->save($shipping_method);

        $response = $this->jsonAs($user , 'post', 'api/orders' , [
            'user_id'=>$user->id ,
            'address_id'=>$address->id ,
            'shipping_method_id'=>$shipping_method->id,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id


        ]);

        Event::assertDispatched(OrderCreated::class, function($event) use ($response){
            return $event->order->id == json_decode($response->getContent())->data->id ;
        });

    }
    public function test_it_return_a_money_instance_for_subtotal()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
        ]);

        $this->assertInstanceOf(Money::class , $order->subtotal);

    }
    public function test_it_return_a_money_instance_for_total()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
        ]);

        $this->assertInstanceOf(Money::class , $order->total());

    }
    public function test_it_adds_the_shipping_price_to_the_total_price()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'subtotal'=>1000 ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
        ]);

        $this->assertEquals( 2000, $order->total()->amount());

    }
    public function test_the_order_has_many_transactions()
    {
        $user = factory(User::class)->create();

        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'subtotal'=>1000 ,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
        ]);

        $order->transactions()->save(
            factory(Transaction::class)->create([
                'order_id'=>$order->id ,
                'user_id'=>$user->id ,
            ])
        );
       $this->assertInstanceOf(Transaction::class ,$order->transactions->first());

    }



}
