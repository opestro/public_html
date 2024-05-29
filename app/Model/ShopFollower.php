<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopFollower extends Model
{
    protected $casts = [
        'shop_id' => 'integer',
        'user_id' => 'integer',
    ];
}
