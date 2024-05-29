<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use App\Model\Order;
use App\Model\ProductCompare;
use App\Model\Seller;
use App\Model\Wishlist;
use App\User;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerSendMessage;
use function App\CPU\translate;

class ChattingController extends Controller
{
    public function __construct(
        private Order $order,
        private Wishlist $wishlist,
        private ProductCompare $compare,

    )
    {

    }
    public function chat_list(Request $request, $type)
    {

        if ($type == 'seller')
        {
            $last_chat = Chatting::with(['shop'])->where('user_id', auth('customer')->id())
                ->whereNotNull(['seller_id', 'user_id'])
                ->orderBy('created_at', 'DESC')
                ->first();
            if (isset($last_chat)) {
                // theme_aster - specific shop start
                if ($request->type =='seller'&& $request->has('id') ){
                    $last_chat = Chatting::with(['shop'])->where(['user_id'=> auth('customer')->id(),'shop_id'=>$request->id])
                        ->whereNotNull(['seller_id', 'user_id'])
                        ->orderBy('created_at', 'DESC')
                        ->first();
                }// theme_aster - specific shop end

                $chattings = Chatting::join('shops', 'shops.id', '=', 'chattings.shop_id')
                    ->select('chattings.*', 'shops.name', 'shops.image')
                    ->where('chattings.user_id', auth('customer')->id())
                    ->where('shop_id', $last_chat->shop_id)
                    ->get();

                $unique_shops = Chatting::join('shops', 'shops.id', '=', 'chattings.shop_id')
                    ->join('sellers', 'sellers.id', '=', 'shops.seller_id')
                    ->select('chattings.*', 'shops.name', 'shops.image','shops.contact','sellers.email as seller_email')
                    ->where('chattings.user_id', auth('customer')->id())
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('shop_id');

                return view(VIEW_FILE_NAMES['user_inbox'], compact('chattings', 'unique_shops', 'last_chat'));
            }
        }elseif ($type == 'delivery-man')
        {
            $last_chat = Chatting::with('delivery_man')->where('user_id', auth('customer')->id())
                ->whereNotNull(['delivery_man_id', 'user_id'])
                ->orderBy('created_at', 'DESC')
                ->first();
            if (isset($last_chat)) {
                // theme_aster - specific shop start
                if ($request->has('id')){
                    $last_chat = Chatting::with('delivery_man')->where('delivery_man_id',$request->id)
                        ->orderBy('created_at', 'DESC')
                        ->first();
                }// theme_aster - specific shop end

                $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name','delivery_men.l_name', 'delivery_men.image')
                    ->where('chattings.user_id', auth('customer')->id())
                    ->where('delivery_man_id', $last_chat->delivery_man_id)
                    ->get();

                $unique_shops = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name','delivery_men.l_name', 'delivery_men.image','delivery_men.email')
                    ->where('chattings.user_id', auth('customer')->id())
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('delivery_man_id');
                return view(VIEW_FILE_NAMES['user_inbox'], compact('chattings', 'unique_shops', 'last_chat'));
            }
        }

        return view(VIEW_FILE_NAMES['user_inbox']);

    }
    public function messages(Request $request)
    {
        if ($request->has('shop_id'))
        {
            Chatting::where(['user_id'=>auth('customer')->id(), 'shop_id'=> $request->shop_id])->update([
                'seen_by_customer' => 1
            ]);

            $shops = Chatting::join('shops', 'shops.id', '=', 'chattings.shop_id')
                ->select('chattings.*', 'shops.name', 'shops.image')
                ->where('user_id', auth('customer')->id())
                ->where('chattings.shop_id', json_decode($request->shop_id))
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        elseif ($request->has('delivery_man_id'))
        {
            Chatting::where(['user_id'=>auth('customer')->id(), 'delivery_man_id'=> $request->delivery_man_id])->update([
                'seen_by_customer' => 1
            ]);

            $shops = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*',  'delivery_men.f_name','delivery_men.l_name', 'delivery_men.image')
                ->where('user_id', auth('customer')->id())
                ->where('chattings.delivery_man_id', json_decode($request->delivery_man_id))
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        return response()->json($shops);


    }

    public function messages_store(Request $request)
    {
        if ($request->message == '') {
            return response()->json(translate('type_something!'), 403);
        }

        if ($request->has('shop_id'))
        {
            $message = $request->message;
            Chatting::create([
                'user_id'          => auth('customer')->id(),
                'shop_id'          => $request->shop_id,
                'seller_id'        => $request->seller_id,
                'message'          => $request->message,
                'sent_by_customer' => 1,
                'seen_by_customer' => 1,
                'seen_by_seller' => 0,
                'created_at'       => now(),
            ]);

            $seller = Seller::find($request->seller_id);
            if($request->has('first_message')){
                $user = auth('customer')->user();
                $customerSendMessage = new CustomerSendMessage($user, $seller);
                Mail::to($seller->email)->send($customerSendMessage);
            }
            $fcm_token = $seller->cm_firebase_token;

        }

        elseif ($request->has('delivery_man_id'))
        {
            $message = $request->message;
            Chatting::create([
                'user_id'          => auth('customer')->id(),
                'delivery_man_id'  => $request->delivery_man_id,
                'message'          => $request->message,
                'sent_by_customer' => 1,
                'seen_by_customer' => 0,
                'created_at'       => now(),
            ]);

            $dm = DeliveryMan::find($request->delivery_man_id);
            $fcm_token = $dm->fcm_token;
        }

        if(!empty($fcm_token)) {
            $data = [
                'title' => translate('message'),
                'description' => $request->message,
                'order_id' => '',
                'image' => '',
            ];
            Helpers::send_push_notif_to_device($fcm_token, $data);
        }

        return response()->json($message);
    }

}
