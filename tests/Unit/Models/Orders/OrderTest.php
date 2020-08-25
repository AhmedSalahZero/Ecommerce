<?php

namespace Tests\Unit\Models\Orders;

use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase ;

    public function test_it_has_a_default_Status_of_pending()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
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
            'shipping_method_id'=>factory(ShippingMethod::class)->create()->id
        ]);
        $this->assertInstanceOf(Address::class, $order->address);

    }
    public function test_it_belongs_to_shipping_method()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id'=>$user->id ,
            'address_id'=>factory(Address::class)->create([
                'user_id'=>$user->id
            ])->id ,
            'shipping_method_id'=>factory(ShippingMethod::class)->create()->id
        ]);
        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }
    public function test_it_must_be_authenticated_user()
    {
        $this->json('post' , 'api/orders')->assertStatus(401);
    }
    public function test_it_requires_an_address()
    {
        $user = factory(User::class)->create();
        $this->jsonAS($user,'post' , 'api/orders')->assertJsonValidationErrors([
            'address_id'
        ]);
    }

    public function test_it_requires_the_address_to_be_exists()
    {
        $user = factory(User::class)->create();

        $this->jsonAS($user,'post' , 'api/orders' ,['address_id'=>888])->assertJsonValidationErrors([
            'address_id'
        ]);

    }
    public function test_it_requires_the_address_belongs_to_the_user()
    {
        $user = factory(User::class)->create();
        $newUser = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id'=>$user->id
        ]);
        $this->jsonAS($newUser,'post' , 'api/orders', ['address_id'=>$address->id])->assertJsonValidationErrors([
            'address_id'
        ]);

    }
    public function test_it_requires_a_shipping_method()
    {
        $user = factory(User::class)->create();


        $this->jsonAS($user,'post' , 'api/orders' )->assertJsonValidationErrors([
            'shipping_method_id'
        ]);

    }
    public function test_it_requires_a_shipping_method_to_be_exists()
    {
        $user = factory(User::class)->create();
        $this->jsonAS($user,'post' , 'api/orders',[
            'shipping_method_id'=>88888
        ] )->assertJsonValidationErrors([
            'shipping_method_id'
        ]);

    }
    public function test_it_requires_a_valid_shipping_method_for_the_given_address(){
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $address = factory(Address::class)->create([
            'user_id'=>$user->id ,
            'country_id'=>$country->id
        ]);
        $shippingMethod =factory(ShippingMethod::class)->create();
  //      $country->shippingMethods()->save($shippingMethod);
        $this->jsonAs($user,'POST' , 'api/orders' , [
            'address_id'=>$address->id ,
            'shipping_method_id'=>$shippingMethod->id
        ])->assertJsonValidationErrors(['shipping_method_id']);

    }
    public function test_it_can_create_an_order()
    {
        $user = factory(User::class)->create([
            'id'=>1000
        ]);
        $country = factory(Country::class)->create();
        $shipping_method = factory(ShippingMethod::class)->create();
        $address=factory(Address::class)->create(['user_id'=>$user->id , 'country_id'=>$country->id]);

        $country->shippingMethods()->save($shipping_method);

        $this->jsonAs($user , 'post', 'api/orders' , [
            'user_id'=>$user->id ,
            'address_id'=>$address->id ,
            'shipping_method_id'=>$shipping_method->id,


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
        ]);
        $product = factory(ProductVariation::class)->create();
        $order->products()->save($product , [
            'quantity'=>8
        ]);
        $this->assertEquals(8 , $order->products->first()->pivot->quantity);
    }







}
