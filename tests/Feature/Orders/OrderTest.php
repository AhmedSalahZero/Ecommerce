<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Country;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_must_be_authenticated_user()
    {
        $this->json('post' , 'api/orders')->assertStatus(401);
    }

    public function test_the_cart_is_empty_after_make_the_order()
    {
        $user = factory(User::class)->create([
            'id' => 1000
        ]);
        $user->cart()->save(factory(ProductVariation::class)->create());
        $country = factory(Country::class)->create();
        $shipping_method = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create(['user_id' => $user->id, 'country_id' => $country->id]);

        $country->shippingMethods()->save($shipping_method);

        $this->jsonAs($user, 'post', 'api/orders', [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping_method->id,
        ]);

        $this->assertEquals(0, $user->cart->count());
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


}
