<?php

namespace Tests\Feature\Addresses;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressStoreTest extends TestCase
{
    use RefreshDatabase ;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_that_name_is_required()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user,'post' , 'api/addresses' )
            ->assertJsonValidationErrors(['name']);

    }
    public function test_that_postal_code_is_required()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user,'post' , 'api/addresses' )
            ->assertJsonValidationErrors(['postal_code']);

    }
    public function test_that_address_1__is_required()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user,'post' , 'api/addresses' )
            ->assertJsonValidationErrors(['address_1']);

    }
    public function test_that_city__is_required()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user,'post' , 'api/addresses' )
            ->assertJsonValidationErrors(['city']);

    }
    public function test_that_country_id_is_required()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user,'post' , 'api/addresses' )
            ->assertJsonValidationErrors(['country_id']);

    }
    public function test_that_country_id_must_be_exist()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user,'post' , 'api/addresses'  , [
            'name'=>'ahmed'  ,
            'postal_code'=>'ads123'  ,
            'city'=>'cairo' ,
            'address_1'=>'mode' ,
            'country_id'=>77777777777
        ])->assertJsonValidationErrors(['country_id']);
    }
    public function test_that_it_store_address()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $this->jsonAs($user,'post' , 'api/addresses'  , [
            'name'=>'ahmed'  ,
            'postal_code'=>'ads123'  ,
            'city'=>'cairo' ,
            'address_1'=>'mode' ,
            'country_id'=>$country->id
        ]);
        $this->assertDatabaseHas('addresses' , ['address_1' =>'mode' ]);

    }


}
