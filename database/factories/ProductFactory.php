<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
        'category_id' => rand(1, 20),
        'cost_price' => $faker->randomFloat(2, 1, 70),
        'sale_price' => $faker->randomFloat(2, 90, 300),
        'units_stock' => rand(1, 500),
        'photo' => Str::random(30) . '.jpg',
        'active' => $faker->boolean(80)
    ];
});
