<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::apiResource('categories', 'CategoryController');
//Route::post('products', 'ProductController@store');
Route::apiResource('products', 'ProductController');
Route::apiResource('orders', 'OrderController')->only(['index', 'store']);
Route::get('reports/sales_period', 'ReportController@salesPeriod');
Route::get('reports/top10_product_sales', 'ReportController@top10ProductSales');
Route::get('reports/ship_sales_info', 'ReportController@shipSalesInfo');
Route::get('reports/email_preview', function() {
    return new App\Mail\SalesInformation(new \App\Services\ReportService(), new \App\Services\OrderService());
});

