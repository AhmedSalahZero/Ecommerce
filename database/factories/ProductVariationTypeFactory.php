<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductVariationType;
use Faker\Generator as Faker;

$factory->define(ProductVariationType::class, function (Faker $faker) {

    return [
        'id'=>($faker->numberBetween(1,300)),
        'name'=>$faker->unique()->name
    ];
});
