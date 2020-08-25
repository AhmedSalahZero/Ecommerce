<?php

namespace Tests\Unit\Models\Adresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class AddressesTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_belongs_to_one_country()
    {
        $address = factory(Address::class)->create([
            'user_id'=>factory(User::class)->create()->id
        ]);

        $this->assertInstanceOf(Country::class , $address->country);

    }

    public function test_it_belongs_to_one_user()
    {
        $address = factory(Address::class)->create([
            'user_id'=>factory(User::class)->create()->id
        ]);

        $this->assertInstanceOf(User::class , $address->user);
    }
}
