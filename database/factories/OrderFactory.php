<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
       'address_id'=>factory(Address::class)->Create(['user_id'=>factory(User::class)->create()])->id ,
        'shipping_method_id'=>factory(ShippingMethod::class)->create() ,
        'subtotal'=> 1000 ,

    ];
});
