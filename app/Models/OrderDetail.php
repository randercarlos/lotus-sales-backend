<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $fillable = ['unit_price', 'qtd', 'order_id', 'product_id'];
    protected $casts = [
        'unit_price' => 'decimal:2',
        'qtd' => 'integer',
        'order_id' => 'integer',
        'product_id' => 'integer'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
