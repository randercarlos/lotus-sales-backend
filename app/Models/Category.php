<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const RECORDS_PER_PAGE = 10;
    protected $table = 'categories';
    protected $fillable = ['name', 'description'];
    protected $hidden = ['created_at', 'updated_at'];
}
