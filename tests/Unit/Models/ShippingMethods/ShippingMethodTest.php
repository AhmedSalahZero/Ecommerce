<?php

namespace Tests\Unit\Models\ShippingMethods;

use App\cart\Money;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_Returns_money_instance_for_the_price()
    {
        $shippingMethod = factory(ShippingMethod::class)->create();
        $this->assertInstanceOf(Money::class,$shippingMethod->price);

    }
    public function test_it_Returns_formatted_price()
    {
        $shippingMethod = factory(ShippingMethod::class)->create();
        $this->assertEquals('$10.00',$shippingMethod->price->formatted());

    }

    public function test_it_returns_belongs_to_many_countries(){
        $shippingMethod= factory(ShippingMethod::class)->create();
        $country =factory(Country::class)->create();
        $shippingMethod->countries()->attach($country);
        $this->assertInstanceOf(Country::class , $shippingMethod->countries->first());

    }



}
