<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_the_is_not_authenticated()
    {
         $this->json('get','api/orders')->assertStatus(401);

    }
    public function test_it_Returns_a_collection_of_orders(){
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $ShippingMethod = factory(ShippingMethod::class)->create();
        $country->shippingMethods($ShippingMethod);
        $product = factory(ProductVariation::class)->create();
        $stock = factory(Stock::class)->create([
            'product_variation_id'=>$product->id ,
            'quantity'=>5
        ]);

        $address =factory(Address::class)->create([
            'user_id'=>$user->id ,
            'country_id'=>$country->id,

        ]);



        $order = factory(Order::Class)->create([
            'user_id'=>$user->id  ,
            'shipping_method_id'=>$ShippingMethod->id ,
            'address_id'=>$address->id,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id

        ]);
        $order->products()->attach($product , [
            'quantity'=>4
        ]);
        $responce = $this->jsonAs($user,'get' , 'api/orders')->assertJsonFragment([
            'id'=>$order->id
        ]);
    }
    public function test_it_Returns_orders_in_the_correct_order(){
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $ShippingMethod = factory(ShippingMethod::class)->create();
        $country->shippingMethods($ShippingMethod);
        $product = factory(ProductVariation::class)->create();
        $stock = factory(Stock::class)->create([
            'product_variation_id'=>$product->id ,
            'quantity'=>5
        ]);

        $address =factory(Address::class)->create([
            'user_id'=>$user->id ,
            'country_id'=>$country->id,

        ]);



        $order = factory(Order::Class)->create([
            'user_id'=>$user->id  ,
            'shipping_method_id'=>$ShippingMethod->id ,
            'address_id'=>$address->id,
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id
        ]);
        $another_order = factory(Order::Class)->create([
            'user_id'=>$user->id  ,
            'shipping_method_id'=>$ShippingMethod->id ,
            'address_id'=>$address->id  ,
            'created_at'=>now()->subDay(),
            'payment_method_id'=>factory(PaymentMethod::class)->create([
                'user_id'=>$user->id
            ])->id

        ]);

        $responce = $this->jsonAs($user,'get' , 'api/orders')->assertSeeInOrder([
            $order->created_at->toDateTimeString() ,
            $another_order->created_at->toDateTimeString()
        ]);
    }

    public function test_it_has_a_pagination(){
        $user = factory(USer::class)->create();
        $this->jsonAs($user,'get' , 'api/orders')->assertJsonStructure([
            'meta' , 'links'
        ]);
    }

}
