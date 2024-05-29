<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\CPU\ProductManager;
use App\CPU\CartManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Banner;
use App\Model\Brand;
use App\Model\BusinessSetting;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Category;
use App\Model\Contact;
use App\Model\Coupon;
use App\Model\Currency;
use App\Model\DealOfTheDay;
use App\Model\DeliveryCountryCode;
use App\Model\DeliveryZipCode;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\HelpTopic;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\DeleteAccountRequest;
use App\Model\ShippingAddress;
use App\Model\Subscription;
use App\Model\ShippingMethod;
use App\Model\Shop;
use App\Model\Order;
use App\Model\Transaction;
use App\Model\Translation;
use App\Traits\CommonTrait;
use App\User;
use App\Model\Notification;
use App\Model\Wishlist;
use App\Model\ShopFollower;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use function App\CPU\translate;
use App\Model\ShippingType;
use Facade\FlareClient\Http\Response;
use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\CaptchaBuilder;
use App\CPU\CustomerManager;
use App\CPU\Convert;
use App\Model\ProductCompare;
use Carbon\Carbon;
use App\Model\Stat;

class WebController extends Controller
{
    use CommonTrait;

    public function __construct(
        private OrderDetail $order_details,
        private Product $product,
        private Wishlist $wishlist,
        private Order $order,
        private Category $category,
        private Brand $brand,
        private Seller $seller,
        private ProductCompare $compare,
    ) {

    }

    public function maintenance_mode()
    {
        $maintenance_mode = Helpers::get_business_settings('maintenance_mode') ?? 0;
        if ($maintenance_mode) {
            return view(VIEW_FILE_NAMES['maintenance_mode']);
        }
        return redirect()->route('home');
    }

    public function spin_game()
    {
        
        if (auth('customer')->check()) {
            $user = auth('customer')->user();
            if ($user->last_spin_win) {
                $now = Carbon::now();
                $lastSpinWin = Carbon::parse($user->last_spin_win);
                $canSpin = $now->diffInMonths($lastSpinWin) >= 1;
            } else {
                $canSpin = true;
            }
        } else {
            $canSpin = false;
        }
        return view(VIEW_FILE_NAMES['spin_game'], ['canSpin' => $canSpin]);
    }
    
    public function updateLastSpinWin(Request $request)
    {
        try {
            // Assuming you have the authenticated user
            $user = auth('customer')->user();

            // Update the last_spin_win column with the current date
            $user->update(['last_spin_win' => now()]);

            return response()->json(['message' => 'Last spin updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

  public function index_2(Request $request)
    {
        
        return view('admin-views.notification.index-notification');
    }
    
    
    public function delete_account()
    {
        
        return view('layouts.delete-account');
    }
    
    public function delete_account_data(Request $request)
    {
        $email = $request->input('identity');
    
        
        $user = User::where('email', $email)->first();
        $seller = Seller::where('email', $email)->first();
    
        if ($user || $seller) {
            
            if ($user) {
                $type = 'user';
            }
            if ($seller) {
                $type = 'seller';
            }
    
            DeleteAccountRequest::create([
                'email' => $email,
                'type' => $type,
            ]);
            
            Toastr::success(translate('Your request has been sent successfully.'));
            return redirect()->route('home');
        } else {
            
            return redirect()->back()->with('error', 'Email not found in our records.');
        }
    }
    
    
    
   public function updateDeviceToken(Request $request)
    {
        $user = User::find(994); 
    
        if ($user) {
            $user->cm_firebase_token = $request->token;
            $user->save();
    
            return response()->json(['Token successfully stored.']);
        } else {
            return response()->json(['User not found.'], 404);
        }
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $user = User::find(994); 
        $FcmToken = "$user->cm_firebase_token";
            
        $serverKey = 'AAAAH6l6PdM:APA91bFPfP-xvSixRRCfsNKXyEP4hNCae79vPEXVRVaNZqpOhepIkjvc_-0l8Ex7R51GVV1asUYyRxq9y-jAN0n62oSh9RdSzorwiuYRE0eM5d3Sy4CZIDE05SxLw4RVM1yAONUMQ9kV'; 
    
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        return back();
    }

    


    public function flash_deals($id)
    {
        $deal = FlashDeal::with(['products.product.reviews', 'products.product' => function($query){
                $query->active();
            }])
            ->where(['id' => $id, 'status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->first();

            $discountPrice = FlashDealProduct::with(['product'])->whereHas('product', function ($query) {
                $query->active();
            })->get()->map(function ($data) {
                return [
                    'discount' => $data->discount,
                    'sellPrice' => isset($data->product->unit_price) ? $data->product->unit_price : 0,
                    'discountedPrice' => isset($data->product->unit_price) ? $data->product->unit_price - $data->discount : 0,

                ];
            })->toArray();


        if (isset($deal)) {
            return view(VIEW_FILE_NAMES['flash_deals'], compact('deal', 'discountPrice'));
        }
        Toastr::warning(translate('not_found'));
        return back();
    }

    public function search_shop(Request $request)
    {
        $key = explode(' ', $request['shop_name']);
        $sellers = Shop::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->whereHas('seller', function ($query) {
            return $query->where(['status' => 'approved']);
        })->paginate(30);
        return view(VIEW_FILE_NAMES['all_stores_page'], compact('sellers'));
    }

    public function all_categories()
    {
        $categories = Category::all();
        return view(VIEW_FILE_NAMES['categories'], compact('categories'));
    }

    public function categories_by_category($id)
    {
        $category = Category::with(['childes.childes'])->where('id', $id)->first();
        return response()->json([
            'view' => view('web-views.partials._category-list-ajax', compact('category'))->render(),
        ]);
    }

    public function all_brands(Request $request)
    {
        $brands = Brand::active()->withCount('brandProducts')
                                ->when($request->has('order_by'), function($query) use($request){
                                    $query->orderBy('name', $request->order_by);
                                })->when($request->has('search'), function($query) use($request){
                                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                                })->latest()->paginate(24);

        $order_by = $request->order_by;
        $search_keyword = $request->search;

        return view(VIEW_FILE_NAMES['all_brands'], compact('brands','order_by','search_keyword'));
    }

    public function all_sellers(Request $request)
    {
        $business_mode=Helpers::get_business_settings('business_mode');
        if(isset($business_mode) && $business_mode=='single')
        {
            Toastr::warning(translate('access_denied!!'));
            return back();
        }
        $sellers = Shop::with(['product.rating' => function($query){
            $query->active();
        }])->withCount('product')->whereHas('seller', function ($query) {
            return $query->approved();
        })->when($request->has('order_by'), function($query) use ($request){
            $query->orderBy('name', $request->order_by);
        })->paginate(12);

        $order_by = $request->order_by;

        return view(VIEW_FILE_NAMES['all_stores_page'], compact('sellers','order_by'));
    }
    
    public function special_sellers(Request $request)
        {
            $business_mode = Helpers::get_business_settings('business_mode');
            if (isset($business_mode) && $business_mode == 'single') {
                Toastr::warning(translate('access_denied!!'));
                return back();
            }
        
            $allowed_addresses = ['PRO 3300DA'];
        
            $sellers = Shop::with(['product.rating' => function ($query) {
                    $query->active();
                }])
                ->withCount('product')
                ->whereHas('seller', function ($query) {
                    return $query->approved();
                })
                ->whereIn('address', $allowed_addresses) 
                ->when($request->has('order_by'), function ($query) use ($request) {
                    $query->orderBy('name', $request->order_by);
                })
                ->paginate(12);
        
            $order_by = $request->order_by;
        
            return view(VIEW_FILE_NAMES['special_stores_page'], compact('sellers', 'order_by'));
        }
        
    public function search_special_shop(Request $request)
    {
        $key = explode(' ', $request['shop_name']);
        $sellers = Shop::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->whereHas('seller', function ($query) {
            return $query->where(['status' => 'approved']);
        })->paginate(30);
        return view(VIEW_FILE_NAMES['special_stores_page'], compact('sellers'));
    }

    

    public function seller_profile($id)
    {
        $seller_info = Seller::find($id);
        return view('web-views.seller-profile', compact('seller_info'));
    }
    
    

    public function searched_products(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required!',
        ]);

        $result = ProductManager::search_products_web($request['name'], $request['category_id']);
        $products = $result['products'];

        if ($products == null) {
            $result = ProductManager::translated_product_search_web($request['name'], $request['category_id']);
            $products = $result['products'];
        }

        return response()->json([
            'result' => view(VIEW_FILE_NAMES['product_search_result'], compact('products'))->render(),
        ]);
    }

    // global search for theme fashion compare list
    public function searched_products_for_compare_list(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required!',
        ]);
        $compare_id = $request['compare_id'];
        $result = ProductManager::search_products_web($request['name']);
        $products = $result['products'];
        if ($products == null) {
            $result = ProductManager::translated_product_search_web($request['name']);
            $products = $result['products'];
        }
        return response()->json([
            'result' => view(VIEW_FILE_NAMES['product_search_result_for_compare_list'], compact('products','compare_id'))->render(),
        ]);

    }

    public function checkout_details(Request $request)
    {
        $cart_group_ids = CartManager::get_cart_group_ids();
        $shippingMethod = Helpers::get_business_settings('shipping_method');

        $physical_product_view = false;
        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product_view = true;
                }
            }
        }

        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();

            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }
            if($physical_product) {
                foreach ($carts as $cart) {
                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart->seller_is == 'admin') {
                            $admin_shipping = ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = ShippingType::where('seller_id', $cart->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }

                    if ($physical_product && $shipping_type == 'order_wise') {
                        $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                        if (!isset($cart_shipping)) {
                            Toastr::info(translate('select_shipping_method_first'));
                            return redirect('shop-cart');
                        }
                    }
                }
            }
        }

        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

        if ($country_restrict_status) {
            $countries = $this->get_delivery_country_array();
        } else {
            $countries = COUNTRIES;
        }

        if ($zip_restrict_status) {
            $zip_codes = DeliveryZipCode::all();
        } else {
            $zip_codes = 0;
        }

        $billing_input_by_customer=Helpers::get_business_settings('billing_input_by_customer');
        $default_location=Helpers::get_business_settings('default_location');
        $shipping_addresses = ShippingAddress::where('customer_id',auth('customer')->id())->where('is_billing',0)->get();
        $billing_addresses = ShippingAddress::where('customer_id',auth('customer')->id())->where('is_billing',1)->get();

        if (count($cart_group_ids) > 0) {
            return view(VIEW_FILE_NAMES['order_shipping'], compact('physical_product_view', 'zip_codes', 'country_restrict_status',
                'zip_restrict_status', 'countries','billing_input_by_customer','default_location','shipping_addresses','billing_addresses'));

        }

        Toastr::info(translate('no_items_in_basket'));
        return redirect('/');
    }

    public function checkout_payment()
    {
        $cart_group_ids = CartManager::get_cart_group_ids();
        $shippingMethod = Helpers::get_business_settings('shipping_method');

        $physical_products[] = false;
        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();
            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }
            $physical_products[] = $physical_product;
        }
        unset($physical_products[0]);

        $cod_not_show = in_array(false, $physical_products);

        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();

            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }

            if($physical_product) {
                foreach ($carts as $cart) {
                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart->seller_is == 'admin') {
                            $admin_shipping = ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = ShippingType::where('seller_id', $cart->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }
                    if ($shipping_type == 'order_wise') {
                        $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                        if (!isset($cart_shipping)) {
                            Toastr::info(translate('select_shipping_method_first'));
                            return redirect('shop-cart');
                        }
                    }
                }
            }
        }

        $order = Order::find(session('order_id'));
        $coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $amount = CartManager::cart_grand_total() - $coupon_discount;
        $inr=Currency::where(['symbol'=>'₹'])->first();
        $usd=Currency::where(['code'=>'USD'])->first();
        $myr=Currency::where(['code'=>'MYR'])->first();

        $cash_on_delivery = Helpers::get_business_settings('cash_on_delivery');
        $digital_payment = Helpers::get_business_settings('digital_payment');
        $wallet_status = Helpers::get_business_settings('wallet_status');
        $offline_payment = Helpers::get_business_settings('offline_payment');
        $ssl_commerz_payment = Helpers::get_business_settings('ssl_commerz_payment');
        $paypal = Helpers::get_business_settings('paypal');
        $stripe = Helpers::get_business_settings('stripe');
        $razor_pay = Helpers::get_business_settings('razor_pay');
        $paystack = Helpers::get_business_settings('paystack');
        $senang_pay = Helpers::get_business_settings('senang_pay');
        $paymob_accept = Helpers::get_business_settings('paymob_accept');
        $bkash = Helpers::get_business_settings('bkash');
        $paytabs = Helpers::get_business_settings('paytabs');
        $mercadopago = Helpers::get_business_settings('mercadopago');
        $flutterwave = Helpers::get_business_settings('flutterwave');
        $paytm = Helpers::get_business_settings('paytm');
        $liqpay = Helpers::get_business_settings('liqpay');

        if (session()->has('address_id') && count($cart_group_ids) > 0) {
            return view(
                VIEW_FILE_NAMES['payment_details'],
                compact(
                    'cod_not_show','order','cash_on_delivery','digital_payment','offline_payment','ssl_commerz_payment','paypal','stripe',
                    'razor_pay','paystack','senang_pay','paymob_accept','bkash','paytabs','mercadopago','flutterwave','paytm','liqpay','wallet_status',
                    'coupon_discount','amount','inr','usd','myr'
                ));
        }

        Toastr::error(translate('incomplete_info'));
        return back();
    }

    public function checkout_complete(Request $request)
    {
        if($request->payment_method != 'cash_on_delivery'){
            return back()->with('error', 'Something went wrong!');
        }
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        $cart_group_ids = CartManager::get_cart_group_ids();
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $physical_product = false;
        foreach($carts as $cart){
            if($cart->product_type == 'physical'){
                $physical_product = true;
            }
        }

        if($physical_product) {
            foreach ($cart_group_ids as $group_id) {
                $data = [
                    'payment_method' => 'cash_on_delivery',
                    'order_status' => 'pending',
                    'payment_status' => 'unpaid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }

            CartManager::cart_clean();

            $stats = Stat::find(1);
            if ($stats) {
                $stats->increment('orders', 1);
            }
            return view(VIEW_FILE_NAMES['order_complete']);
        }

        return back()->with('error', 'Something went wrong!');
    }

    public function offline_payment_checkout_complete(Request $request)
    {
        if($request->payment_method != 'offline_payment'){
            return back()->with('error', 'Something went wrong!');
        }
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        $cart_group_ids = CartManager::get_cart_group_ids();

        foreach ($cart_group_ids as $group_id) {
            $data = [
                'payment_method' => 'offline_payment',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => $request->transaction_ref,
                'payment_by' => $request->payment_by,
                'payment_note' => $request->payment_note,
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean();


        return view(VIEW_FILE_NAMES['order_complete']);
    }
    public function checkout_complete_wallet(Request $request = null)
    {
        $cartTotal = CartManager::cart_grand_total();
        $user = Helpers::get_customer($request);
        if( $cartTotal > $user->wallet_balance)
        {
            Toastr::warning(translate('inefficient balance in your wallet to pay for this order!!'));
            return back();
        }else{
            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $data = [
                    'payment_method' => 'pay_by_wallet',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }

            CustomerManager::create_wallet_transaction($user->id, Convert::default($cartTotal), 'order_place','order payment');
            CartManager::cart_clean();
        }

        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            return redirect()->route('payment-success');
        }
        return view(VIEW_FILE_NAMES['order_complete']);
    }

    public function order_placed()
    {
        return view(VIEW_FILE_NAMES['order_complete']);
    }

    public function shop_cart(Request $request)
    {
        if (auth('customer')->check() && Cart::where(['customer_id' => auth('customer')->id()])->count() > 0) {
            return view(VIEW_FILE_NAMES['cart_list']);
        }
        Toastr::info(translate('no_items_in_basket'));
        return redirect('/');
    }

    //ajax filter (category based)
    public function seller_shop_product(Request $request, $id)
    {
        $products = Product::active()->with('shop')->where(['added_by' => 'seller'])
        ->where('user_id', $id)
        ->whereJsonContains('category_ids', [
            ['id' => strval($request->category_id)],
            ])
            ->paginate(12);
        $shop = Shop::where('seller_id', $id)->first();
        if ($request['sort_by'] == null) {
            $request['sort_by'] = 'latest';
        }

        if ($request->ajax()) {
            return response()->json([
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products'))->render(),
            ], 200);

        }

        return view(VIEW_FILE_NAMES['shop_view_page'], compact('products', 'shop'))->with('seller_id', $id);
    }

    public function quick_view(Request $request)
    {
        $product = ProductManager::get_product($request->product_id);
        $product->increment('views');
        $order_details = OrderDetail::where('product_id', $product->id)->get();
        $wishlists = Wishlist::where('product_id', $product->id)->get();
        $wishlist_status = Wishlist::where(['product_id'=>$product->id, 'customer_id'=>auth('customer')->id()])->count();
        $countOrder = count($order_details);
        $countWishlist = count($wishlists);
        $relatedProducts = Product::with(['reviews'])->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();
        $current_date = date('Y-m-d');
        $seller_vacation_start_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
        $seller_vacation_end_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
        $seller_temporary_close = ($product->added_by == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

        $temporary_close = Helpers::get_business_settings('temporary_close');
        $inhouse_vacation = Helpers::get_business_settings('vacation_add');
        $inhouse_vacation_start_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_start_date'] : null;
        $inhouse_vacation_end_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_end_date'] : null;
        $inhouse_vacation_status = $product->added_by == 'admin' ? $inhouse_vacation['status'] : false;
        $inhouse_temporary_close = $product->added_by == 'admin' ? $temporary_close['status'] : false;

        // Newly Added From Blade
        $overallRating = ProductManager::get_overall_rating($product->reviews);
        $rating = ProductManager::get_rating($product->reviews);
        $reviews_of_product = Review::where('product_id',$product->id)->latest()->paginate(2);
        $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings');
        $more_product_from_seller = Product::active()->where('added_by',$product->added_by)->where('id','!=',$product->id)->where('user_id',$product->user_id)->latest()->take(5)->get();

        return response()->json([
            'success' => 1,
            'product' => $product,
            'view' => view(VIEW_FILE_NAMES['product_quick_view_partials'], compact('product', 'countWishlist', 'countOrder',
                'relatedProducts', 'current_date', 'seller_vacation_start_date', 'seller_vacation_end_date', 'seller_temporary_close',
                'inhouse_vacation_start_date', 'inhouse_vacation_end_date','inhouse_vacation_status', 'inhouse_temporary_close','wishlist_status','overallRating'))->render(),
        ]);
    }

    public function discounted_products(Request $request)
    {
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with(['reviews']);

        if ($request['data_from'] == 'category') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['id']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
        }

        if ($request['data_from'] == 'latest') {
            $query = $porduct_data->orderBy('id', 'DESC');
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')->get();
            $product_ids = [];
            foreach ($reviews as $review) {
                array_push($product_ids, $review['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'best-selling') {
            $details = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'most-favorite') {
            $details = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'featured') {
            $query = Product::with(['reviews'])->active()->where('featured', 1);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $query = $porduct_data->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        }

        if ($request['data_from'] == 'discounted_products') {
            $query = Product::with(['reviews'])->active()->where('discount', '!=', 0);
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            return "low";
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query;
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];

        $products = $fetched->paginate(5)->appends($data);

        if ($request->ajax()) {
            return response()->json([
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products'))->render()
            ], 200);
        }
        if ($request['data_from'] == 'category') {
            $data['brand_name'] = Category::find((int)$request['id'])->name;
        }
        if ($request['data_from'] == 'brand') {
            $data['brand_name'] = Brand::active()->find((int)$request['id'])->name;
        }

        return view(VIEW_FILE_NAMES['products_view_page'], compact('products', 'data'), $data);

    }

    public function viewWishlist(Request $request)
    {
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;

        $wishlists = Wishlist::with([
            'product_full_info',
            'product_full_info.compare_list'=>function($query){
                return $query->where('user_id', auth('customer')->id() ?? 0);
            }
        ])
        ->whereHas('wishlistProduct', function ($q) use ($request) {
            $q->when($request['search'],function ($query) use ($request) {
                $query->where('name', 'like', "%{$request['search']}%")
                    ->orWhereHas('category', function ($qq) use ($request) {
                        $qq->where('name', 'like', "%{$request['search']}%");
                    });
            });
        })
        ->where('customer_id', auth('customer')->id())->paginate(15);

        return view(VIEW_FILE_NAMES['account_wishlist'], compact('wishlists', 'brand_setting'));
    }

    public function storeWishlist(Request $request)
    {
        if ($request->ajax()) {
            if (auth('customer')->check()) {
                $wishlist = Wishlist::where('customer_id', auth('customer')->id())->where('product_id', $request->product_id)->first();
                if ($wishlist) {
                    $wishlist->delete();

                    $countWishlist = Wishlist::whereHas('wishlistProduct',function($q){
                        return $q;
                    })->where('customer_id', auth('customer')->id())->count();
                    $product_count = Wishlist::where(['product_id' => $request->product_id])->count();
                    session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

                    return response()->json([
                        'error' => translate("wishlist_Removed"),
                        'value' => 2,
                        'count' => $countWishlist,
                        'product_count' => $product_count
                    ]);

                } else {
                    $wishlist = new Wishlist;
                    $wishlist->customer_id = auth('customer')->id();
                    $wishlist->product_id = $request->product_id;
                    $wishlist->save();

                    $countWishlist = Wishlist::whereHas('wishlistProduct',function($q){
                        return $q;
                    })->where('customer_id', auth('customer')->id())->count();

                    $product_count = Wishlist::where(['product_id' => $request->product_id])->count();
                    session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

                    return response()->json([
                        'success' => translate("Product has been added to wishlist"),
                        'value' => 1, 'count' => $countWishlist,
                        'id' => $request->product_id,
                        'product_count' => $product_count
                    ]);
                }

            } else {
                return response()->json(['error' => translate('login_first'), 'value' => 0]);
            }
        }
    }

    public function deleteWishlist(Request $request)
    {
        $this->wishlist->where(['product_id' => $request['id'], 'customer_id' => auth('customer')->id()])->delete();
        $data = "Product has been remove from wishlist!";
        $wishlists = $this->wishlist->where('customer_id', auth('customer')->id())->paginate(15);
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        session()->put('wish_list', $this->wishlist->where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
        return response()->json([
            'success' => $data,
            'count' => count($wishlists),
            'id' => $request->id,
            'wishlist' => view(VIEW_FILE_NAMES['account_wishlist_partials'], compact('wishlists', 'brand_setting'))->render(),
        ]);
    }

    public function delete_wishlist_all(){
        $this->wishlist->where('customer_id', auth('customer')->id())->delete();
        session()->put('wish_list', $this->wishlist->where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
        return redirect()->back();
    }

    //order Details

    public function orderdetails()
    {
        return view('web-views.orderdetails');
    }

    public function chat_for_product(Request $request)
    {
        return $request->all();
    }

    public function supportChat()
    {
        return view('web-views.users-profile.profile.supportTicketChat');
    }

    public function error()
    {
        return view('web-views.404-error-page');
    }

    public function contact_store(Request $request)
    {
        //recaptcha validation
        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {

            try {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                            $response = $value;
                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                            $response = \file_get_contents($url);
                            $response = json_decode($response);
                            if (!$response->success) {
                                $fail(\App\CPU\translate('ReCAPTCHA Failed'));
                            }
                        },
                    ],
                ]);

            } catch (\Exception $exception) {
                return back()->withErrors(\App\CPU\translate('Captcha Failed'))->withInput($request->input());
            }
        } else {
            if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
                Session::forget('default_captcha_code');
                return back()->withErrors(\App\CPU\translate('Captcha Failed'))->withInput($request->input());
            }
        }

        $request->validate([
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'mobile_number.required' => 'Mobile Number is Empty!',
            'subject.required' => ' Subject is Empty!',
            'message.required' => 'Message is Empty!',

        ]);
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();
        Toastr::success(translate('Your Message Send Successfully'));
        return back();
    }

    public function captcha($tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if(Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function order_note(Request $request)
    {
        if ($request->has('order_note')) {
            session::put('order_note', $request->order_note);
        }
        return response()->json();
    }

    public function digital_product_download($id)
    {
        $order_data = OrderDetail::with('order.customer')->find($id);
        $customer_id = auth('customer')->id();
        if($order_data->order->customer->id != $customer_id){
            Toastr::info(translate('Invalid customer'));
            return redirect('/');
        }

        if( $order_data->product->digital_product_type == 'ready_product' && $order_data->product->digital_file_ready) {
            $file_path = storage_path('app/public/product/digital-product/' .$order_data->product->digital_file_ready);
        }else{
            $file_path = storage_path('app/public/product/digital-product/' . $order_data->digital_file_after_sell);
        }

        return \response()->download($file_path);
    }

    public function subscription(Request $request)
    {
        $subscription_email = Subscription::where('email',$request->subscription_email)->first();
        if(isset($subscription_email))
        {
            Toastr::info(translate('You already subcribed this site!!'));
            return back();
        }else{
            $new_subcription = new Subscription;
            $new_subcription->email = $request->subscription_email;
            $new_subcription->save();

            Toastr::success(translate('Your subscription successfully done!!'));
            return back();

        }

    }
    public function review_list_product(Request $request)
    {
        $productReviews =Review::where('product_id',$request->product_id)->latest()->paginate(2, ['*'], 'page', $request->offset);
        $checkReviews =Review::where('product_id',$request->product_id)->latest()->paginate(2, ['*'], 'page', ($request->offset+1));
        return response()->json([
            'productReview'=> view(VIEW_FILE_NAMES['product_reviews_partials'],compact('productReviews'))->render(),
            'not_empty'=>$productReviews->count(),
            'checkReviews'=>$checkReviews->count(),
        ]);
    }
    public function review_list_shop(Request $request)
    {
        $seller_id = 0;
        if($request->shop_id != 0)
        {
            $seller_id = Shop::where('id',$request->shop_id)->first()->seller_id;
        }
        $product_ids = Product::active()
            ->when($request->shop_id == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($request->shop_id != 0, function ($query) use ($seller_id) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $seller_id);
            })
            ->pluck('id')->toArray();

        $productReviews =Review::whereIn('product_id',$product_ids)->latest()->paginate(4, ['*'], 'page', $request->offset);
        $checkReviews =Review::whereIn('product_id',$product_ids)->latest()->paginate(4, ['*'], 'page', ($request->offset+1));
        // dd($productReviews);
        return response()->json([
            'productReview'=> view(VIEW_FILE_NAMES['product_reviews_partials'],compact('productReviews'))->render(),
            'not_empty'=>$productReviews->count(),
            'checkReviews'=>$checkReviews->count(),
        ]);
    }
    public function product_view_style(Request $request)
    {
        Session::put('product_view_style', $request->value);
        return response()->json([
            'message'=>translate('View_style_updated')."!",
        ]);
    }

}
