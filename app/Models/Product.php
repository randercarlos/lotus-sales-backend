<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    const PHOTO_UPLOAD_FOLDER = 'products/';
    const RECORDS_PER_PAGE = 10;
    protected $table = 'products';
    protected $fillable = ['name', 'category_id', 'cost_price', 'sale_price', 'units_stock', 'photo', 'active'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'units_stock' => 'integer',
        'category_id' => 'integer',
        'active' => 'boolean'
    ];
    protected $attributes = [   // set default values whether these fields are not defined
        'cost_price' => 0,
        'sale_price' => 0,
        'units_stock' => 0,
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function orders() {
        return $this->belongsToMany(Order::class, 'order_details');
    }

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }

    public function getPhotoFile()
    {
        return Storage::get(self::PHOTO_UPLOAD_FOLDER . '/' . $this->getAttributeValue('photo'));
    }

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = (int) $value;
    }

}
