<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ShopFollower;
use Carbon\Carbon;

use App\Model\Shop;
use App\User;

class ShopFollowerController extends Controller
{
    public function __construct(
        private ShopFollower $shop_follower,
    ) {

    }

    public function shop_follow(Request $request)
    {
        if (auth('customer')->check()) {
            $shopFollower = $this->shop_follower->where(['user_id'=>auth('customer')->id(),'shop_id'=>$request->shop_id])->first();
            if ($shopFollower) {
                $shopFollower->delete();
                $followers = $this->shop_follower->where(['shop_id'=>$request->shop_id])->count();

                return response()->json([
                    'text' => translate("follow"),
                    'message' => translate("unfollow_successfully")."!",
                    'value' => 2,
                    'followers' => $followers,
                ]);

            } else {
                $this->shop_follower->insert([
                    'user_id'=>auth('customer')->id(),
                    'shop_id'=>$request->shop_id,
                    'created_at'=>Carbon::now(),
                ]);
                $followers = $this->shop_follower->where(['shop_id'=>$request->shop_id])->count();

                return response()->json([
                    'text' => translate("Unfollow"),
                    'message' => translate("follow_successfully")."!",
                    'value' => 1,
                    'followers' => $followers,
                ]);
            }
        }else{
            return response()->json([
                'message' => translate("Login_first"),
                'value' => 0,
            ]);
        }

    }
    
    
   



   

}
