<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['order_date'];
    protected $dates = ['order_date'];
    protected $casts = ['order_date' => 'date'];
    protected $hidden = ['created_at', 'updated_at'];

    public function products() {
        return $this->belongsToMany(Product::class, 'order_details');
    }

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }
}
