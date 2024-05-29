<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompare extends Model
{
    use HasFactory;
        protected $casts = [
        'product_id'  => 'integer',
        'user_id'     => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->active();
    }
}
