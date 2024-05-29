<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Product;
use App\Model\Seller;
use App\Model\Shops;
use App\Model\WithdrawRequest;
use App\Model\SellerWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Review;
use App\Model\OrderTransaction;
use App\Model\DeliveryMan;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Mail\SubscriptionWarningMail;
use App\Mail\SubscriptionEndsMail;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        $current_date = date('Y-m-d');

        $sellers = Seller::with(['orders', 'product'])
            ->when($search, function($query) use($search){
                $key = explode(' ', $search);
                foreach ($key as $value) {
                    $query->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            })
            ->latest()
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);

        return view('admin-views.seller.index', compact('sellers', 'search', 'current_date'));
    }
    


    public function active_sellers(Request $request)
    {
        $query_param = [];
        $search_approved = $request['search_approved'];
        $current_date = Carbon::now()->toDateString();
    
        $approvedSellers = Seller::with(['orders', 'product'])
            ->where('status', 'approved');
    
        if ($search_approved) {
            if ($search_approved == 'Last2Days') {
                $twoDaysLater = Carbon::now()->addDays(2)->toDateString();
                $approvedSellers->whereHas('shop', function ($query) use ($current_date, $twoDaysLater) {
                    $query->whereDate('subscription_end_date', '>', $current_date)
                    ->whereDate('subscription_end_date', '=', $twoDaysLater);
                });
            } elseif ($search_approved == 'EndDay') {
                $approvedSellers->whereHas('shop', function ($query) use ($current_date) {
                    $query->whereDate('subscription_end_date', '=', $current_date);
                });
            } elseif ($search_approved == 'EndDayPassed') {
            $approvedSellers->whereHas('shop', function ($query) use ($current_date) {
                $query->whereDate('subscription_end_date', '<', $current_date);
            });
            }else {
                $key = explode(' ', $search_approved);
                foreach ($key as $value) {
                    $approvedSellers->where(function ($query) use ($value) {
                        $query->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    });
                }
            }
        }
    
        $approvedSellers = $approvedSellers->latest()
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);
    
        return view('admin-views.seller.approved-sellers', compact('approvedSellers', 'search_approved', 'current_date'));
    }

    public function send_warnings(Request $request)
        {
           $current_date = now()->toDateString();
           $sentEmailCount = 0;
    
           $approvedSellers = Seller::with(['orders', 'product'])
                ->where('status', 'approved')
                ->whereHas('shop', function ($query) use ($current_date) {
                    $query->whereDate('subscription_end_date', '=', now()->addDays(2)->toDateString())
                        ->whereDate('subscription_end_date', '>', $current_date);
            })
            ->get();
            
            foreach ($approvedSellers as $seller) {
                Mail::to($seller->email)->send(new SubscriptionWarningMail($seller));
                $sentEmailCount++;
            }
    
            Toastr::info("{$sentEmailCount} Warning messages has been sent successfully");
            return back();
        }

  

    public function view(Request $request, $id, $tab = null)
    {
        $seller = Seller::find($id);
        $totalAmount = 0;
        if(!isset($seller))
        {
            Toastr::error('Seller not found,It may be deleted!');
            return back();
        }
        $current_date = date('Y-m-d');
       
            

        if ($tab == 'order') {
            $id = $seller->id;
            $orders = Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$id])->where('order_type','default_type')->latest()->paginate(Helpers::pagination_limit());

            return view('admin-views.seller.view.order', compact('seller', 'orders'));
        } else if ($tab == 'product') {
            $products = Product::where('added_by', 'seller')->where('user_id', $seller->id)->latest()->paginate(Helpers::pagination_limit());
            return view('admin-views.seller.view.product', compact('seller', 'products'));
        } else if ($tab == 'setting') {
            $commission = $request['commission'];
            if ($request->has('commission')) {
                request()->validate([
                    'commission' => 'required | numeric | min:1',
                ]);

                if ($request['commission_status'] == 1 && $request['commission'] == null) {
                    Toastr::error('You did not set commission percentage field.');
                    //return back();
                } else {
                    $seller = Seller::find($id);
                    $seller->sales_commission_percentage = $request['commission_status'] == 1 ? $request['commission'] : null;
                    $seller->save();

                    Toastr::success('Commission percentage for this seller has been updated.');
                }
            }
            $commission = 0;
            if ($request->has('gst')) {
                if ($request['gst_status'] == 1 && $request['gst'] == null) {
                    Toastr::error('You did not set GST number field.');
                    //return back();
                } else {
                    $seller = Seller::find($id);
                    $seller->gst = $request['gst_status'] == 1 ? $request['gst'] : null;
                    $seller->save();

                    Toastr::success('GST number for this seller has been updated.');
                }
            }
            if ($request->has('seller_pos')) {

                    $seller = Seller::find($id);
                    $seller->pos_status = $request->seller_pos;
                    $seller->save();

                    Toastr::success('Seller pos permission updated.');

            }
           
            
            //return back();
            return view('admin-views.seller.view.setting', compact('seller'));
        } else if ($tab == 'transaction') {
            $transactions = OrderTransaction::where('seller_is','seller')->where('seller_id',$seller->id);

            $query_param = [];
            $search = $request['search'];
            if ($request->has('search'))
            {
                $key = explode(' ', $request['search']);
                $transactions = $transactions->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('order_id', 'like', "%{$value}%")
                            ->orWhere('transaction_id', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }else{
                $transactions = $transactions;
            }
            $status = $request['status'];
            if ($request->has('status') && $status!='all')
            {
                $key = explode(' ', $request['status']);
                $transactions = $transactions->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('status', 'like', "%{$value}%");
                    }
                });
                $query_param = ['status' => $request['status']];
            }
               $transactions = $transactions->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

            return view('admin-views.seller.view.transaction', compact('seller', 'transactions','search','status'));

        } else if ($tab == 'review') {
            $sellerId = $seller->id;

            $query_param = [];
            $search = $request['search'];
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $product_id = Product::where('added_by','seller')->where('user_id',$sellerId)->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->where('name', 'like', "%{$value}%");
                    }
                })->pluck('id')->toArray();

                $reviews = Review::with(['product'])
                    ->whereIn('product_id',$product_id);

                $query_param = ['search' => $request['search']];
            } else {
                $reviews = Review::with(['product'])->whereHas('product', function ($query) use ($sellerId) {
                    $query->where('user_id', $sellerId)->where('added_by', 'seller');
                });
            }
            //dd($reviews->count());
            $reviews = $reviews->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

            return view('admin-views.seller.view.review', compact('seller', 'reviews', 'search'));
        }
        
       $membershipOffer = $seller->shop->address;
       $subscriptionEnd = $seller->shop->subscription_end_date;
       $membershipPrice = 0;
       if ($seller->shop->duration > 0) {
            if ($membershipOffer == "BASIC 1200DA") {
                $membershipPrice = 1200;
            } elseif ( $membershipOffer == "PRIME 2200DA") {
                $membershipPrice = 2200;
            } elseif ( $membershipOffer == "PRO 3300DA") {
                $membershipPrice = 3300;
            }
        
            $totalAmount = $membershipPrice * $seller->shop->duration;
        }
        return view('admin-views.seller.view', compact('seller','current_date','totalAmount','membershipPrice','subscriptionEnd'));
    }

     public function updateStatus(Request $request)
    {
        $order = Seller::findOrFail($request->id);
        $order->status = $request->status;
        if ($request->status == "approved") {
            Toastr::success('Seller has been approved successfully');
        } else if ($request->status == "rejected") {
            Toastr::info('Seller has been rejected successfully');
        } else if ($request->status == "suspended") {
            $order->auth_token = Str::random(80);
            Toastr::info('Seller has been suspended successfully');
        }
        $order->save();
        return back();
    }
    
    public function updateStatus2(Request $request)
    {
        $order = Seller::findOrFail($request->id);
        $order->status = $request->status;
        $seller = Seller::find($request->id);
        #Mail::to($seller->email)->send(new SellerApproved($seller));
        if ($request->status == "approved") {
            $shop = $seller->shop;

            $duration = $shop->duration;
            $address = $shop->address;
    
            if ($shop->created_at == $shop->subscription_last_update && in_array($address, ['PRIME 2200DA','PRO 3300DA'])) {
                $subscriptionEnd = now()->addMonths($duration + 1);
            } elseif ($shop->created_at != $shop->subscription_last_update) {
                $subscriptionEnd = now()->addMonths($duration);
            } else {
                $subscriptionEnd = now()->addMonths($duration);
            }
            
            DB::table('shops')->updateOrInsert(
                ['id' => $shop->id], 
                ['subscription_end_date' => $subscriptionEnd]
            );


            Mail::to($seller->email)->send(new \App\Mail\SellerApproved($seller));
            Toastr::success('Seller has been approved successfully');
        } else if ($request->status == "rejected") {
            Toastr::info('Seller has been rejected successfully');
        } else if ($request->status == "suspended") {
            $shop = $seller->shop;
            DB::table('shops')->updateOrInsert(
                ['id' => $shop->id], 
                ['subscription_last_update' => now()]
            );
            $order->auth_token = Str::random(80);
            Mail::to($seller->email)->send(new SubscriptionEndsMail($seller));
            Toastr::info('Seller has been suspended successfully');
        }
        $order->save();
        return back();
    }
    public function viewInfo($id)
    {
    
    $seller = Seller::find($id);

    
    return view('admin-views.seller.view-edit-seller', ['seller' => $seller]);
    }
    
  public function updateInfo(Request $request)
    {
        $this->validate($request, [
            'duration' => 'required|integer|between:1,12',
            'abonnement_type' => 'required|in:BASIC 1200DA,PRIME 2200DA,PRO 3300DA',
        ]);
    
        $seller = Seller::find($request->id);
        $seller->shop->duration = $request->duration;
        $seller->shop->address = $request->abonnement_type;
        $seller->shop->save();
        
        Toastr::success('Seller information updated successfully');
        return redirect()->route('admin.sellers.view', ['id' => $seller->id]);

    }

    public function order_list($seller_id)
    {
        $orders = Order::where(['seller_id'=> $seller_id, 'seller_is'=> 'seller'])
                ->latest()
                ->paginate(Helpers::pagination_limit());

        $seller = Seller::findOrFail($seller_id);
        return view('admin-views.seller.order-list', compact('orders', 'seller'));
    }

    public function product_list($seller_id)
    {
        $product = Product::where(['user_id' => $seller_id, 'added_by' => 'seller'])->latest()->paginate(Helpers::pagination_limit());
        $seller = Seller::findOrFail($seller_id);
        return view('admin-views.seller.porduct-list', compact('product', 'seller'));
    }

    public function order_details($order_id, $seller_id)
    {
        $order = Order::with('shipping')->where(['id' => $order_id])->first();
        $shipping_method = Helpers::get_business_settings('shipping_method');
        $delivery_men = DeliveryMan::where('is_active', 1)->when($order->seller_is == 'admin', function ($query) {
            $query->where(['seller_id' => 0]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'sellerwise_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => $order['seller_id']]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'inhouse_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => 0]);
        })->get();
        return view('admin-views.seller.order-details', compact('order', 'seller_id','delivery_men'));
    }

    public function withdraw()
    {
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['seller'])
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->orderBy('id', 'desc')
            ->latest()
            ->paginate(Helpers::pagination_limit());

        return view('admin-views.seller.withdraw', compact('withdraw_req'));
    }

    public function withdraw_list_export_excel(Request $request){
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_requests = WithdrawRequest::with(['seller', 'withdraw_method'])
            ->whereNull('delivery_man_id')
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->orderBy('id', 'desc')->get();

        $withdraw_requests->map(function ($query) {
            //company info
            $query->shop_name = isset($query->seller) ? $query->seller->shop->name : '';
            $query->shop_phone = isset($query->seller) ? $query->seller->shop->contact : '';
            $query->shop_address = isset($query->seller) ? $query->seller->shop->address : '';
            $query->shop_email = isset($query->seller) ? $query->seller->email : '';

            $query->withdrawal_amount = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($query->amount));
            $query->status = $query->approved == 0 ? 'Pending' : ($query->approved == 1 ? 'Approved':'Denied');
            $query->note = $query->transaction_note;

            //method info
            $query->withdraw_method_name = isset($query->withdraw_method) ? $query->withdraw_method->method_name : '';
            if(!empty($query->withdrawal_method_fields)){
                foreach (json_decode($query->withdrawal_method_fields) as $key=>$field) {
                    $query[$key] = $field;
                }
            }
        });

        foreach ($withdraw_requests as $key=>$item) {
            unset($item['id']);
            unset($item['seller_id']);
            unset($item['admin_id']);
            unset($item['delivery_man_id']);
            unset($item['request_updated_by']);
            unset($item['created_at']);
            unset($item['updated_at']);
            unset($item['amount']);
            unset($item['approved']);
            unset($item['withdrawal_method_fields']);
            unset($item['withdrawal_method_id']);
            unset($item['withdraw_id']);
            unset($item['transaction_note']);
            unset($item['provider']);
            unset($item['withdraw_method']);
        }
        return (new FastExcel($withdraw_requests))->download(time() . '-file.xlsx');
    }

    public function withdraw_view($withdraw_id, $seller_id)
    {
        $withdraw_request = WithdrawRequest::with(['seller'])->where(['id' => $withdraw_id])->first();
        $withdrawal_method = json_decode($withdraw_request->withdrawal_method_fields);

        return view('admin-views.seller.withdraw-view', compact('withdraw_request', 'withdrawal_method'));
    }

    public function withdrawStatus(Request $request, $id)
    {
        $withdraw = WithdrawRequest::find($id);
        $withdraw->approved = $request->approved;
        $withdraw->transaction_note = $request['note'];
        if ($request->approved == 1) {
            SellerWallet::where('seller_id', $withdraw->seller_id)->increment('withdrawn', $withdraw['amount']);
            SellerWallet::where('seller_id', $withdraw->seller_id)->decrement('pending_withdraw', $withdraw['amount']);
            $withdraw->save();
            Toastr::success('Seller Payment has been approved successfully');
            return redirect()->route('admin.sellers.withdraw_list');
        }

        SellerWallet::where('seller_id', $withdraw->seller_id)->increment('total_earning', $withdraw['amount']);
        SellerWallet::where('seller_id', $withdraw->seller_id)->decrement('pending_withdraw', $withdraw['amount']);
        $withdraw->save();
        Toastr::info('Seller Payment request has been Denied successfully');
        return redirect()->route('admin.sellers.withdraw_list');

    }

    public function sales_commission_update(Request $request, $id)
    {
        if ($request['status'] == 1 && $request['commission'] == null) {
            Toastr::error('You did not set commission percentage field.');
            return back();
        }

        $seller = Seller::find($id);
        $seller->sales_commission_percentage = $request['status'] == 1 ? $request['commission'] : null;
        $seller->save();

        Toastr::success('Commission percentage for this seller has been updated.');
        return back();
    }
    public function add_seller()
    {
        return view('admin-views.seller.add-new-seller');
    }
    
}
