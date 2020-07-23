<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OrderDetail;
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(OrderDetail::class, function (Faker $faker) {
    return [
        'unit_price' => Product::find(rand(1, 20))->sale_price,
        'qtd' => rand(1, 15),
        'order_id' => rand(1, 20),
        'product_id' => rand(1, 20),
    ];
});
