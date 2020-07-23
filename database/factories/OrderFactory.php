<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'order_date' => Carbon::now()->subMonths(rand(1, 20))
    ];
});
