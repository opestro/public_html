<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Model\DealOfTheDay;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\Wishlist;
use App\Model\ProductTag;
use App\Model\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\CPU\translate;

class ProductDetailsController extends Controller
{
    public function product($slug)
    {
        $theme_name = theme_root_path();

        return match ($theme_name){
            'default' => self::default_theme($slug),
            'theme_aster' => self::theme_aster($slug),
            'theme_fashion' => self::theme_fashion($slug),
            'theme_all_purpose' => self::theme_all_purpose($slug),
        };
    }

    public function default_theme($slug){
        $product = Product::active()->with(['reviews','seller.shop'])->where('slug', $slug)->first();
        if ($product != null) {
            $overallRating = ProductManager::get_overall_rating($product->reviews);
            $wishlist_status = Wishlist::where(['product_id'=>$product->id, 'customer_id'=>auth('customer')->id()])->count();
            $reviews_of_product = Review::where('product_id', $product->id)->latest()->paginate(2);
            $rating = ProductManager::get_rating($product->reviews);
            $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
            $more_product_from_seller = Product::active()->where('added_by', $product->added_by)->where('id', '!=', $product->id)->where('user_id', $product->user_id)->latest()->take(5)->get();
            if ($product->added_by == 'seller') {
                $products_for_review = Product::active()->where('added_by', $product->added_by)->where('user_id', $product->user_id)->withCount('reviews')->get();
            } else {
                $products_for_review = Product::where('added_by', 'admin')->where('user_id', $product->user_id)->withCount('reviews')->get();
            }

            $total_reviews = 0;
            foreach ($products_for_review as $item) {
                $total_reviews += $item->reviews_count;
            }

            $countOrder = OrderDetail::where('product_id', $product->id)->count();
            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $relatedProducts = Product::with(['reviews'])->active()->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();
            $deal_of_the_day = DealOfTheDay::where('product_id', $product->id)->where('status', 1)->first();
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

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'countWishlist', 'countOrder', 'relatedProducts',
                'deal_of_the_day', 'current_date', 'seller_vacation_start_date', 'seller_vacation_end_date', 'seller_temporary_close',
                'inhouse_vacation_start_date', 'inhouse_vacation_end_date', 'inhouse_vacation_status', 'inhouse_temporary_close','overallRating',
                'wishlist_status','reviews_of_product','rating','total_reviews','products_for_review','more_product_from_seller','decimal_point_settings'));
        }

        Toastr::error(translate('not_found'));
        return back();
    }

    public function theme_aster($slug){
        $product = Product::active()
            ->with([
            'reviews','seller.shop',
            'wish_list'=>function($query){
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compare_list'=>function($query){
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
            ])->where('slug', $slug)->first();
        if ($product != null) {
            $current_date = date('Y-m-d H:i:s');

            $countOrder = OrderDetail::where('product_id', $product->id)->count();
            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $wishlist_status = Wishlist::where(['product_id'=>$product->id, 'customer_id'=>auth('customer')->id()])->count();

            $relatedProducts = Product::with([
                'reviews', 'flash_deal_product.flash_deal',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();

            $relatedProducts?->map(function ($product) use($current_date){
                $flash_deal_status=0;
                $flash_deal_end_date = 0;
                if(count($product->flash_deal_product)>0){
                    $flash_deal = $product->flash_deal_product[0]->flash_deal;
                    if($flash_deal) {
                        $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                        $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                        $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                        $flash_deal_end_date = $flash_deal->end_date;
                    }
                }
                $product['flash_deal_status'] = $flash_deal_status;
                $product['flash_deal_end_date'] = $flash_deal_end_date;
                return $product;
            });

            $deal_of_the_day = DealOfTheDay::where('product_id', $product->id)->where('status', 1)->first();
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

            $overallRating = ProductManager::get_overall_rating($product->reviews);

            $rating = ProductManager::get_rating($product->reviews);
            $reviews_of_product = Review::where('product_id', $product->id)->latest()->paginate(2);
            $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings');
            $more_product_from_seller = Product::active()->where('added_by', $product->added_by)->where('id', '!=', $product->id)->where('user_id', $product->user_id)->latest()->take(5)->get();

            if ($product->added_by == 'seller') {
                $products_for_review = Product::active()->where('added_by', $product->added_by)->where('user_id', $product->user_id)->withCount('reviews')->get();
            } else {
                $products_for_review = Product::where('added_by', 'admin')->where('user_id', $product->user_id)->withCount('reviews')->get();
            }

            $total_reviews = 0;
            foreach ($products_for_review as $item) {
                $total_reviews += $item->reviews_count;
            }

            $product_ids = Product::where(['added_by'=> $product->added_by, 'user_id'=>$product->user_id])->pluck('id');

            $rating_status = Review::whereIn('product_id', $product_ids);
            $rating_count = $rating_status->count();
            $avg_rating = $rating_count != 0 ? $rating_status->avg('rating') : 0;
            $rating_percentage = round(($avg_rating * 100) / 5);
            $shop = $product->seller->shop;
            $product->increment('views');
            
            

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'wishlist_status','countWishlist',
                'countOrder', 'relatedProducts', 'deal_of_the_day', 'current_date', 'seller_vacation_start_date', 'seller_vacation_end_date',
                'seller_temporary_close', 'inhouse_vacation_start_date', 'inhouse_vacation_end_date', 'inhouse_vacation_status', 'inhouse_temporary_close',
                'overallRating','decimal_point_settings','more_product_from_seller','products_for_review', 'total_reviews','rating','reviews_of_product',
                'avg_rating','rating_percentage','shop'));
        }

        Toastr::error(translate('not_found'));
        return back();

    }

    public function theme_fashion($slug)
    {
        $product = Product::active()->with(['reviews','seller.shop','compare_list'=>function($query){
            return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
        }])->where('slug', $slug)->first();

        if ($product != null) {

            $tags = ProductTag::where('product_id', $product->id)->pluck('tag_id');
            Tag::whereIn('id', $tags)->increment('visit_count');

            $current_date = date('Y-m-d H:i:s');

            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $wishlist_status = Wishlist::where(['product_id'=>$product->id, 'customer_id'=>auth('customer')->id()])->count();

            $relatedProducts = Product::active()
                                        ->where('category_id', $product->category_id)
                                        ->where('sub_category_id', $product->sub_category_id)
                                        ->where('sub_sub_category_id', $product->sub_sub_category_id)
                                        ->where('id', '!=', $product->id)->count();

            $seller_vacation_start_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
            $seller_vacation_end_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
            $seller_temporary_close = ($product->added_by == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

            $temporary_close = Helpers::get_business_settings('temporary_close');
            $inhouse_vacation = Helpers::get_business_settings('vacation_add');
            $inhouse_vacation_start_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_start_date'] : null;
            $inhouse_vacation_end_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_end_date'] : null;
            $inhouse_vacation_status = $product->added_by == 'admin' ? $inhouse_vacation['status'] : false;
            $inhouse_temporary_close = $product->added_by == 'admin' ? $temporary_close['status'] : false;

            $overallRating = ProductManager::get_overall_rating($product->reviews);
            $product_reviews_count = $product->reviews->count();

            $ratting_status_positive = $product_reviews_count != 0 ? ($product->reviews->where('rating','>=', 4)->count()*100) / $product_reviews_count : 0;
            $ratting_status_good = $product_reviews_count != 0 ? ($product->reviews->where('rating', 3)->count()*100) / $product_reviews_count : 0;
            $ratting_status_neutral = $product_reviews_count != 0 ? ($product->reviews->where('rating', 2)->count()*100) / $product_reviews_count : 0;
            $ratting_status_negative = $product_reviews_count != 0 ? ($product->reviews->where('rating','=', 1)->count()*100) / $product_reviews_count : 0;
            $ratting_status = [
                'positive' => $ratting_status_positive,
                'good' => $ratting_status_good,
                'neutral' => $ratting_status_neutral,
                'negative' => $ratting_status_negative,
            ];

            $rating = ProductManager::get_rating($product->reviews);
            $reviews_of_product = Review::where('product_id', $product->id)->latest()->paginate(2);
            $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings');
            $more_product_from_seller = Product::active()->with(['wish_list'=>function($query){
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            }])->where('added_by', $product->added_by)->where('id', '!=', $product->id)->where('user_id', $product->user_id)->latest()->take(5)->get();

            if ($product->added_by == 'seller') {
                $products_for_review = Product::active()->where('added_by', $product->added_by)->where('user_id', $product->user_id)->withCount('reviews')->get();
            } else {
                $products_for_review = Product::where('added_by', 'admin')->where('user_id', $product->user_id)->withCount('reviews')->get();
            }

            $total_reviews = 0;
            foreach ($products_for_review as $item) {
                $total_reviews += $item->reviews_count;
            }

            $product_ids = Product::where(['added_by'=> $product->added_by, 'user_id'=>$product->user_id])->pluck('id');

            $rating_status = Review::whereIn('product_id', $product_ids);
            $rating_count = $rating_status->count();
            $avg_rating = $rating_count != 0 ? $rating_status->avg('rating') : 0;
            $rating_percentage = round(($avg_rating * 100) / 5);

            // more stores start
            $more_seller = Seller::approved()->with(['shop','product.reviews'])
                ->withCount(['product'=> function($query){
                    $query->active();
                }])
                ->inRandomOrder()
                ->take(7)->get();

            $more_seller = $more_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach($product->reviews as $reviews)
                    {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });
            //end more stores

            // new stores
            $new_seller = Seller::approved()->with(['shop', 'product.reviews'])
                ->withCount(['product'=> function($query){
                    $query->active();
                }])
                ->latest()
                ->take(7)->get();

            $new_seller = $new_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach($product->reviews as $reviews)
                    {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });
            //end new stores

            $delivery_info = ProductManager::get_products_delivery_charge($product, $product->minimum_order_qty);

            // top_rated products
            $products_top_rated = Product::with(['rating','reviews','wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                }, 'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }])->active()
                ->withCount(['reviews'])->orderBy('reviews_count', 'DESC')
                ->take(12)->get();

            $products_this_store_top_rated = Product::with(['rating','reviews','wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                }, 'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }])->active()
                ->where(['added_by'=>$product->added_by,'user_id'=>$product->user_id])
                ->withCount(['reviews'])->orderBy('reviews_count', 'DESC')
                ->take(12)->get();

            $products_latest = Product::active()->with(['reviews','rating','wish_list'=>function($query){
                                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                            }, 'compare_list'=>function($query){
                                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                            }])->latest()->take(12)->get();

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'wishlist_status','countWishlist',
                'relatedProducts', 'current_date', 'seller_vacation_start_date', 'seller_vacation_end_date','ratting_status','products_latest',
                'seller_temporary_close', 'inhouse_vacation_start_date', 'inhouse_vacation_end_date', 'inhouse_vacation_status', 'inhouse_temporary_close',
                'overallRating','decimal_point_settings','more_product_from_seller','products_for_review', 'total_reviews','rating','reviews_of_product',
                'avg_rating','rating_percentage','more_seller','new_seller','delivery_info','products_top_rated','products_this_store_top_rated'));
        }

        Toastr::error(translate('not_found'));
        return back();
    }
    public function theme_all_purpose($slug)
    {
        $product = Product::active()->with(['reviews','seller.shop'])->where('slug', $slug)->first();
        if ($product != null) {

            $tags = ProductTag::where('product_id', $product->id)->pluck('tag_id');
            Tag::whereIn('id', $tags)->increment('visit_count');

            $current_date = date('Y-m-d H:i:s');

            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $wishlist_status = Wishlist::where(['product_id'=>$product->id, 'customer_id'=>auth('customer')->id()])->count();

            $relatedProducts = Product::with(['reviews', 'flash_deal_product.flash_deal'])->active()->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();
            $relatedProducts?->map(function ($product) use($current_date){
                $flash_deal_status=0;
                $flash_deal_end_date = 0;
                if(count($product->flash_deal_product)>0){
                    $flash_deal = $product->flash_deal_product[0]->flash_deal;
                    if($flash_deal) {
                        $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                        $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                        $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                        $flash_deal_end_date = $flash_deal->end_date;
                    }
                }
                $product['flash_deal_status'] = $flash_deal_status;
                $product['flash_deal_end_date'] = $flash_deal_end_date;
                return $product;
            });

            $seller_vacation_start_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
            $seller_vacation_end_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
            $seller_temporary_close = ($product->added_by == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

            $temporary_close = Helpers::get_business_settings('temporary_close');
            $inhouse_vacation = Helpers::get_business_settings('vacation_add');
            $inhouse_vacation_start_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_start_date'] : null;
            $inhouse_vacation_end_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_end_date'] : null;
            $inhouse_vacation_status = $product->added_by == 'admin' ? $inhouse_vacation['status'] : false;
            $inhouse_temporary_close = $product->added_by == 'admin' ? $temporary_close['status'] : false;

            $overall_rating = ProductManager::get_overall_rating($product->reviews);
            $product_reviews_count = $product->reviews->count();

            $ratting_status_positive = $product_reviews_count != 0 ? ($product->reviews->where('rating','>=', 4)->count()*100) / $product_reviews_count : 0;
            $ratting_status_good = $product_reviews_count != 0 ? ($product->reviews->where('rating', 3)->count()*100) / $product_reviews_count : 0;
            $ratting_status_neutral = $product_reviews_count != 0 ? ($product->reviews->where('rating', 2)->count()*100) / $product_reviews_count : 0;
            $ratting_status_negative = $product_reviews_count != 0 ? ($product->reviews->where('rating','=', 1)->count()*100) / $product_reviews_count : 0;
            $ratting_status = [
                'positive' => $ratting_status_positive,
                'good' => $ratting_status_good,
                'neutral' => $ratting_status_neutral,
                'negative' => $ratting_status_negative,
            ];

            $rating = ProductManager::get_rating($product->reviews);
            $reviews_of_product = Review::where('product_id', $product->id)->latest()->paginate(2);
            $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings');
            $more_product_from_seller = Product::active()->where('added_by', $product->added_by)->where('id', '!=', $product->id)->where('user_id', $product->user_id)->latest()->take(5)->get();
            $more_product_from_seller_count = Product::active()->where('added_by', $product->added_by)->where('id', '!=', $product->id)->where('user_id', $product->user_id)->count();

            if ($product->added_by == 'seller') {
                $products_for_review = Product::active()->where('added_by', $product->added_by)->where('user_id', $product->user_id)->withCount('reviews')->get();
            } else {
                $products_for_review = Product::where('added_by', 'admin')->where('user_id', $product->user_id)->withCount('reviews')->get();
            }

            $total_reviews = 0;
            foreach ($products_for_review as $item) {
                $total_reviews += $item->reviews_count;
            }

            $product_ids = Product::where(['added_by'=> $product->added_by, 'user_id'=>$product->user_id])->pluck('id');

            $rating_status = Review::whereIn('product_id', $product_ids);
            $rating_count = $rating_status->count();
            $avg_rating = $rating_count != 0 ? $rating_status->avg('rating') : 0;
            $rating_percentage = round(($avg_rating * 100) / 5);

            // more stores start
            $more_seller = Seller::approved()->with(['shop','product.reviews'])
                ->withCount(['product'=> function($query){
                    $query->active();
                }])
                ->inRandomOrder()
                ->take(7)->get();

            $more_seller = $more_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach($product->reviews as $reviews)
                    {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });
            //end more stores

            // new stores
            $new_seller = Seller::approved()->with(['shop', 'product.reviews'])
                ->withCount(['product'=> function($query){
                    $query->active();
                }])
                ->latest()
                ->take(7)->get();

            $new_seller = $new_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach($product->reviews as $reviews)
                    {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });
            //end new stores

            $delivery_info = ProductManager::get_products_delivery_charge($product, $product->minimum_order_qty);

            // top_rated products
            $products_top_rated = Product::with(['rating','reviews'])->active()
                ->withCount(['reviews'])->orderBy('reviews_count', 'DESC')
                ->take(12)->get();

            $products_this_store_top_rated = Product::with(['rating','reviews'])->active()
                ->where(['added_by'=>$product->added_by,'user_id'=>$product->user_id])
                ->withCount(['reviews'])->orderBy('reviews_count', 'DESC')
                ->take(12)->get();

            $products_latest = Product::active()->with(['reviews','rating'])->latest()->take(12)->get();

            return view(VIEW_FILE_NAMES['products_details'], compact('product', 'wishlist_status','countWishlist',
                'relatedProducts', 'current_date', 'seller_vacation_start_date', 'seller_vacation_end_date','ratting_status','products_latest',
                'seller_temporary_close', 'inhouse_vacation_start_date', 'inhouse_vacation_end_date', 'inhouse_vacation_status', 'inhouse_temporary_close',
                'overall_rating','decimal_point_settings','more_product_from_seller','products_for_review', 'total_reviews','rating','reviews_of_product',
                'avg_rating','rating_percentage','more_seller','new_seller','delivery_info','products_top_rated','products_this_store_top_rated','more_product_from_seller_count'));
        }

        Toastr::error(translate('not_found'));
        return back();
    }
}
