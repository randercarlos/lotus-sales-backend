<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderService extends AbstractService
{
    protected $model;

    public function __construct() {
        $this->model = new Order();
    }

    public function findAll(Request $request) {

        $query = Order::with('orderDetails.product')
            ->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->_limit ?? Product::RECORDS_PER_PAGE, ['*'], 'page', $request->_page ?? 1);

        // dd($query->get());

        return $query;
    }

    public function totalSales() {
        return Order::count();
    }

    public function create(Request $request) {
        // dd($request->all());
        if (!$order = Order::create($request->all())) {
            return response()->json('Fail on create order', 500);
        }

        if ($request->has('order_details') && sizeof($request->order_details) > 0) {
            $orderDetails = collect($request->order_details)->map(function ($item) use ($order) {
                return ['unit_price' => $item['unit_price'], 'qtd' => $item['qtd'],
                    'product_id' => $item['product']['id'], 'order_id' => $order->id ];
            });

            $order->orderDetails()->createMany($orderDetails->toArray());
        }

        return $order;
    }

}
