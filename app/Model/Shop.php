<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $casts = [
        'seller_id ' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'subscription_end_date'  => 'datetime',
        'subscription_last_update'  => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function product(){
        return $this->hasMany(Product::class, 'user_id', 'seller_id')->where(['added_by'=>'seller', 'status'=>1]);
    }

    public function scopeActive($query){
        return $query->whereHas('seller', function ($query) {
            $query->where(['status' => 'approved']);
        });
    }
}
