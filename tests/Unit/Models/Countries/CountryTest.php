<?php

namespace Tests\Unit\Models\Countries;

use App\Models\Country;
use App\Models\ShippingMethod;
use Tests\TestCase;

class CountryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_returns_shipping_method_instance()
    {
       $country =factory(Country::class)->create();
       $ShippingMethod = factory(ShippingMethod::class)->create();
       $country->shippingMethods()->attach($ShippingMethod);
       $this->assertInstanceOf(ShippingMethod::class,$country->shippingMethods->first());

    }
}
