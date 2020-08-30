<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PaymentMethod;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(PaymentMethod::class, function (Faker $faker) {
    return [
        'card_type'=>'visa Card' , 
        'last_four'=>'4242' , 
        'provider_id'=>str::random(10)
    ];
});
