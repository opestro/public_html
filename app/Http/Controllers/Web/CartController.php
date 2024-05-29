<?php

namespace App\Http\Controllers\Web;


use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Color;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\ShippingType;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct(
        private OrderDetail $order_details,
        private Product $product,
    ) {

    }
    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $price = 0;

        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
        }

        foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation(json_decode($product->variation)[$i]->price, $product['tax'], $product['tax_type']):0;
                    $update_tax = $tax * $request->quantity;
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation($product->unit_price, $product['tax'], $product['tax_type']) : 0;
            $update_tax = $tax * $request->quantity;
            $discount = Helpers::get_product_discount($product, $product->unit_price);
            $price = $product->unit_price - $discount + $tax;
            $quantity = $product->current_stock;
        }

        $delivery_info = [];
        if(theme_root_path() == 'theme_fashion') {
            $delivery_info = ProductManager::get_products_delivery_charge($product, $request->quantity);
        }

        return [
            'price' => \App\CPU\Helpers::currency_converter($price * $request->quantity),
            'discount' => \App\CPU\Helpers::currency_converter($discount),
            'tax' => $product->tax_model=='exclude' ? \App\CPU\Helpers::currency_converter($tax) : 'incl.',
            'update_tax' => $product->tax_model=='exclude' ? \App\CPU\Helpers::currency_converter($update_tax) : 'incl.', // for others theme
            'quantity' => $product['product_type'] == 'physical' ? $quantity : 100,
            'delivery_cost' => isset($delivery_info['delivery_cost']) ? \App\CPU\Helpers::currency_converter($delivery_info['delivery_cost']):0,
        ];
    }

    public function addToCart(Request $request)
    {
        $cart = CartManager::add_to_cart($request);
        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');
        return response()->json($cart);
    }

    public function updateNavCart()
    {
        return response()->json(['data' => view(VIEW_FILE_NAMES['products_cart_partials'])->render(), 'mobile_nav' => view(VIEW_FILE_NAMES['products_mobile_nav_partials'])->render()]);
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        $user = Helpers::get_customer();
        if ($user == 'offline') {
            if (session()->has('offline_cart') == false) {
                session()->put('offline_cart', collect([]));
            }
            $cart = session('offline_cart');

            $new_collection = collect([]);
            foreach ($cart as $item) {
                if ($item['id'] !=  $request->key) {
                    $new_collection->push($item);
                }
            }

            session()->put('offline_cart', $new_collection);
            return response()->json([$new_collection, 'message'=>translate('Item_has_been_removed_from_cart')]);
        } else {
            Cart::where(['id' => $request->key, 'customer_id' => auth('customer')->id()])->delete();
        }

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');
        session()->forget('shipping_method_id');
        session()->forget('order_note');

        return response()->json(['data' => view(VIEW_FILE_NAMES['products_cart_details_partials'])->render(), 'message'=>translate('Item_has_been_removed_from_cart')]);
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $response = CartManager::update_cart_qty($request);

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');

        if ($response['status'] == 0) {
            return response()->json($response);
        }
        return response()->json(view(VIEW_FILE_NAMES['products_cart_details_partials'])->render());
    }

    //updated the quantity for a cart item
    public function updateQuantity_guest(Request $request)
    {

        $sub_total=0;

        if(Auth::guard('customer')->check())
        {
            $response = CartManager::update_cart_qty($request);
            $cart=CartManager::get_cart();
            session()->forget('coupon_code');
            session()->forget('coupon_type');
            session()->forget('coupon_bearer');
            session()->forget('coupon_discount');
            session()->forget('coupon_seller_id');

            $product = Cart::find($request->key);
            $quantity_price = Helpers::currency_converter($product['price']*(int)$product['quantity']);
            $discount_price = Helpers::currency_converter(($product['price']-$product['discount'])*(int)$product['quantity']);

            foreach($cart as $cartItem)
            {
                $sub_total+=($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'];
            }
            $total_price = Helpers::currency_converter($sub_total);

            if ($response['status'] == 0) {
                return response()->json([
                    'status'=>$response['status'],
                    'message'=> $response['message'],
                    'qty'=>$response['status'] == 0 ? $response['qty']:$request->quantity,
                ]);
            }

            return response()->json([
                'status'=>$response['status'],
                'message'=> translate('successfully_updated!'),
                'qty'=>$response['status'] == 0 ? $response['qty']:$request->quantity,
                'total_price'=>$total_price,
                'discount_price'=>$discount_price,
                'quantity_price'=>$quantity_price,
            ]);
        }else{
            $cart=CartManager::get_cart();
            $offline_cart = session()->get('offline_cart');
            $quantity_price = 0;
            $discount_price = 0;
            $status = 1;
            $message = translate('successfully_updated!');

            $new_cart = collect();
            foreach ($offline_cart as $key => $value) {
                if ($value["id"] == $request->key) {

                    $product = Product::find($value['product_id']);
                    $count = count(json_decode($product->variation));

                    if ($count) {
                        for ($i = 0; $i < $count; $i++) {
                            if (json_decode($product->variation)[$i]->type == $value['variant']) {
                                if (json_decode($product->variation)[$i]->qty < $request->quantity) {
                                    $status = 0;
                                    $qty = $value['quantity'];
                                    $message = translate('sorry_stock_is_limited');
                                }
                            }
                        }
                    } else if (($product['product_type'] == 'physical') && $product['current_stock'] < $request->quantity) {
                        $status = 0;
                        $qty = $value['quantity'];
                        $message = translate('sorry_stock_is_limited');
                    }
                    if ($status) {
                        $qty = $request->quantity;
                        $value["quantity"] = $request->quantity;
                    }

                    $quantity_price = Helpers::currency_converter($value['price']*(int)$qty);
                    $discount_price = Helpers::currency_converter(($value['price']-$value['discount'])*(int)$qty);
                }
                $new_cart->push($value);
            }

            foreach($cart as $cartItem)
            {
                $sub_total+=($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'];
            }
            $total_price = Helpers::currency_converter($sub_total);

            session()->put('offline_cart', $new_cart);

            return response()->json([
                'status'=>$status,
                'message'=> $message,
                'qty'=>$qty,
                'total_price'=>$total_price,
                'discount_price'=>$discount_price,
                'quantity_price'=>$quantity_price,
            ]);
        }
    }

    public function order_again(Request $request){
        $order_products = $this->order_details->where('order_id', $request->order_id)->get();
        $order_product_count = $order_products->count();
        $add_to_cart_count = 0;

        foreach ($order_products as $order_product) {
            $product = $this->product->active()->find($order_product->product_id);
            if($product) {
                $product_valid = true;
                if (($product['product_type'] == 'physical') && ($product['current_stock'] < $order_product['qty']) && ($product['min_qty'] >= $order_product['qty'])) {
                    $product_valid = false;
                }

                if ($product_valid) {
                    $color = null;
                    $choices = [];
                    if ($order_product->variation) {
                        $variation = json_decode($order_product->variation, true);

                        if (isset($variation['color']) && $variation['color']) {
                            $color = Color::where('name', $variation['color'])->first()->code;
                            $i = 1;
                            foreach ($variation as $key => $var) {
                                if ($key != 'color') {
                                    $choices['choice_' . $i] = $var;
                                    $i++;
                                }
                            }
                        } else {
                            $i = 1;
                            foreach ($variation as $key => $var) {
                                $choices['choice_' . $i] = $var;
                                $i++;
                            }
                        }
                    }

                    $user = Helpers::get_customer($request);
                    //generate group id
                    $cart_check = Cart::where([
                        'customer_id' => $user->id,
                        'seller_id' => ($product->added_by == 'admin') ? 1 : $product->user_id,
                        'seller_is' => $product->added_by])->first();

                    if (isset($cart_check)) {
                        $cart_group_id = $cart_check['cart_group_id'];
                    } else {
                        $cart_group_id = $user->id . '-' . Str::random(5) . '-' . time();
                    }
                    //generate group id end

                    $price = 0;
                    if (json_decode($product->variation)) {
                        $count = count(json_decode($product->variation));

                        for ($i = 0; $i < $count; $i++) {
                            if (json_decode($product->variation)[$i]->type == $order_product->variant) {
                                $price = json_decode($product->variation)[$i]->price;
                                if (json_decode($product->variation)[$i]->qty < $order_product->quantity) {
                                    $product_valid = false;
                                }
                            }
                        }
                    } else {
                        $price = $product->unit_price;
                    }

                    $tax = Helpers::tax_calculation($price, $product['tax'], 'percent');
                    if ($product_valid && $price != 0) {
                        $cart_exist = Cart::where(['customer_id'=>$user->id, 'variations'=>$order_product->variation, 'product_id'=>$order_product->product_id])->first();
                        if(!$cart_exist){
                            $cart = new Cart();
                            $cart['cart_group_id'] = $cart_group_id;
                            $cart['color'] = $color;
                            $cart['product_id'] = $order_product->product_id;
                            $cart['product_type'] = $product->product_type;
                            $cart['choices'] = json_encode($choices);
                            $cart['variations'] = $order_product->variation;
                            $cart['variant'] = $order_product->variant;
                            $cart['customer_id'] = $user->id ?? 0;
                            $cart['quantity'] = $product->minimum_order_qty;
                            $cart['price'] = $price;
                            $cart['tax'] = $tax;
                            $cart['tax_model'] = $product->tax_model;
                            $cart['slug'] = $product->slug;
                            $cart['name'] = $product->name;
                            $cart['discount'] = Helpers::get_product_discount($product, $price);
                            $cart['thumbnail'] = $product->thumbnail;
                            $cart['seller_id'] = ($product->added_by == 'admin') ? 1 : $product->user_id;
                            $cart['seller_is'] = $product->added_by;
                            $cart['shipping_cost'] = $product->product_type == 'physical' ? CartManager::get_shipping_cost_for_product_category_wise($product, $order_product->qty) : 0;
                            if ($product->added_by == 'seller') {
                                $cart['shop_info'] = Shop::where(['seller_id' => $product->user_id])->first()->name;
                            } else {
                                $cart['shop_info'] = Helpers::get_business_settings('company_name');
                            }

                            $shippingMethod = Helpers::get_business_settings('shipping_method');

                            if ($shippingMethod == 'inhouse_shipping') {
                                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

                            } else {
                                if ($product->added_by == 'admin') {
                                    $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                    $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                } else {
                                    $seller_shipping = ShippingType::where('seller_id', $product->user_id)->first();
                                    $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                }
                            }

                            $cart['shipping_type'] = $shipping_type;
                            $cart->save();

                            $add_to_cart_count++;
                        }
                    }
                }
            }
        }

        if($order_product_count == $add_to_cart_count){
            session()->forget('coupon_code');
            session()->forget('coupon_type');
            session()->forget('coupon_bearer');
            session()->forget('coupon_discount');
            session()->forget('coupon_seller_id');
            session()->forget('shipping_method_id');
            session()->forget('order_note');

            return [
                'status' => 1,
                'message' => translate('added_to_cart_successfully!')
            ];
        }elseif($add_to_cart_count>0){
            return [
                'status' => 1,
                'message' => translate($add_to_cart_count.'_item_added_to_cart_successfully!')
            ];
        }{
            return [
                'status' => 0,
                'message' => translate('all_items_were_not_added_to_cart_as_they_are_currently_unavailable_for_purchase!')
            ];
        }
    }
    function update_variation(Request $request){
        $product = Product::find($request->product_id);
        $user = Helpers::get_customer($request);
        $str = '';
        $variations = [];
        $price = 0;
        $discount = 0;
        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
            $variations['color'] = $str;
        }
        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        $choices = [];
        foreach (json_decode($product->choice_options) as $key => $choice) {
            $choices[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }
        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation(json_decode($product->variation)[$i]->price, $product['tax'], $product['tax_type']):0;
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation($product->unit_price, $product['tax'], $product['tax_type']) : 0;
            $discount = Helpers::get_product_discount($product, $product->unit_price);
            $price = $product->unit_price - $discount + $tax;
            $quantity = $product->current_stock;
        }
        $cart = Cart::where(['product_id' => $request->product_id, 'customer_id' => $user->id, 'variant' => $str])->first();
        if (isset($cart) == false) {
            $cart = Cart::find($request->id);
            $cart['color']          = $request->has('color') ? $request['color'] : null;
            $cart['choices']        = json_encode($choices);

            $cart['variations']     = json_encode($variations);
            $cart['variant']        = $str;

            //Check the string and decreases quantity for the stock
            if ($str != null) {
                $count = count(json_decode($product->variation));
                for ($i = 0; $i < $count; $i++) {
                    if (json_decode($product->variation)[$i]->type == $str) {
                        $price = json_decode($product->variation)[$i]->price;
                        if (json_decode($product->variation)[$i]->qty < $request['quantity']) {
                            return [
                                'status' => 0,
                                'message' => translate('out_of_stock!')
                            ];
                        }
                    }
                }
            } else {
                $price = $product->unit_price;
            }
            $cart['price'] = $price;
            $cart['discount'] = $discount;
            $cart['tax'] = $tax;
            $cart['quantity'] = $request['quantity'];
            $cart->save();

            return [
                'status' => 1,
                'message' => translate('successfully_added!'),
                'price' => \App\CPU\Helpers::currency_converter($price),
                'discount' => \App\CPU\Helpers::currency_converter($discount*$request['quantity']),
                'data' => view(VIEW_FILE_NAMES['products_cart_details_partials'])->render()
            ];
        } else {
            return [
                'status' => 0,
                'message' => translate('already_added!')
            ];
        }
    }
    public function remove_all_cart(){
        Cart::where('customer_id', auth('customer')->id())->delete();
        return redirect()->back();
    }
}
