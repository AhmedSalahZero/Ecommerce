<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressShippingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

        public function test_it_fails_if_not_auth(){
            $this->json('get' , 'api/addresses/1/shipping')->assertStatus(401);
        }
        public function test_it_fails_if_the_address_not_found(){
            $user = factory(User::class)->create();
            $this->jsonAs($user,'get' ,'api/addresses/1/shipping')->assertStatus(404);
        }
        public function test_it_fails_if_the_user_does_not_own_the_address(){
            $user = factory(User::class)->create();
            $address1=factory(Address::class)->make();
            $address2=factory(Address::class)->create([
                'user_id'=>factory(User::class)->create()->id
            ]);
            $user->addresses()->save($address1);
            $this->jsonAs($user,'get',"api/addresses/{$address2->id}/shipping")
                ->assertStatus(403);
        }
        public function test_it_shows_shipping_Methods()
        {
            $user = factory(User::class)->create();
            $country = factory(Country::class)->create();

            $address =factory(Address::class)->make([
                'user_id'=>$user->id ,
                'country_id'=>$country->id
            ]);
            $shippingMethod = factory(ShippingMethod::class)->create();
            $country->shippingMethods()->attach($shippingMethod);
            $user->addresses()->save($address);
            $this->jsonAs($user ,'get' ,"api/addresses/{$address->id}/shipping" )
                ->assertJsonFragment([
                    'name'=>$address->country->shippingMethods->first()->name
                ]);


        }

}
