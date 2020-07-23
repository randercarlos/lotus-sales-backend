<?php

namespace App\Services;

use App\Http\Requests\ProductFormRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function salesPeriod(Request $request) {

        $totalRevenue = 0;
        $totalProfit = 0;
        $query = Order::query();

        $query->when($request->query('from'), function ($q) use ($request) {
            return $q->whereDate('order_date', '>= ', $request->query('from'));
        });

        $query->when($request->query('to'), function ($q) use ($request) {
            return $q->whereDate('order_date', '<=', $request->query('to'));
        });

        if ($query->get()->count() > 0) {
            $orders = $query->get()->pluck('id')->toArray();

            $orderDetails = OrderDetail::with('product')->whereIn('order_id', $orders)->get();
            if ($orderDetails->count() > 0) {
                $orderDetails->each(function ($item) use (&$totalRevenue, &$totalProfit) {
                    $totalRevenue += $item->unit_price * $item->qtd;
                    $totalProfit += ($item->unit_price - $item->product->cost_price) * $item->qtd;
                });
            }
        }

        return ['revenue' => $totalRevenue, 'profit' => $totalProfit];
    }

    public function top10ProductSales($request, $type = null) {

        $products = Product::with('category')->withCount('orderDetails')->get();
        $products = $products->when($type == 1 || $request->query('type', 1) == 1, function ($q) use ($request) {
            return $q->sortByDesc('order_details_count');
        });

        $products = $products->when($type == 2 || $request->query('type') == 2, function ($q) use ($request) {
            return $q->sortBy('order_details_count');
        });

        return $products->take(10)->values();
    }

}
