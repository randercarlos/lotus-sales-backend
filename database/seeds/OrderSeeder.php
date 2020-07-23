<?php

use Illuminate\Database\Seeder;
use App\Models\Order;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 20; $i++) {
            $order = new Order();
            $order->order_date = Carbon::now()->subMonths(rand(1, 20));

            $order->save();
        }
        // factory(Order::class, 20);
    }
}
