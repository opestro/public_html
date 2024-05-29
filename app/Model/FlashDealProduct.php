<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FlashDealProduct extends Model
{
    protected $casts = [

        'product_id'    => 'integer',
        'discount'      => 'float',
        'flash_deal_id' => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flash_deal(){
        return $this->belongsTo(FlashDeal::class)->where(['deal_type'=>'flash_deal','status'=>1]);
    }

    public function feature_deal(){
        return $this->belongsTo(FlashDeal::class, 'flash_deal_id', 'id')->where(['deal_type'=>'feature_deal','status'=>1]);
    }
}
