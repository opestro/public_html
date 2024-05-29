<?php

namespace App\Http\Controllers\api\v4;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\Product;

class FlashDealController extends Controller
{
    public function get_flash_deal()
    {
        try {
            $flash_deals = FlashDeal::with(['products.product.seller.shop'=>function($query){
                    $query->whereHas('product',function($q){
                        $q->active();
                    });
                }])
                ->where(['deal_type'=>'flash_deal', 'status' => 1])
                ->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->first();
            return response()->json($flash_deals, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }

    }

    public function get_products($deal_id)
    {
        $p_ids = FlashDealProduct::with(['product'])
                ->whereHas('product',function($q){
                    $q->active();
                })
                ->where(['flash_deal_id' => $deal_id])
                ->pluck('product_id')->toArray();

        if (count($p_ids) > 0) {
            return response()->json(Helpers::product_data_formatting(Product::with(['rating'])->whereIn('id', $p_ids)->get(), true), 200);
        }

        return response()->json([], 200);
    }
}
