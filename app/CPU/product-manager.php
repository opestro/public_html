<?php

namespace App\CPU;

use App\Model\ProductCompare;
use App\Model\Review;
use App\Model\Product;
use App\Model\OrderDetail;
use App\Model\Translation;
use App\Model\ShippingMethod;
use App\Model\Wishlist;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Model\ShippingType;
use App\Model\CategoryShippingCost;

class ProductManager
{
    public static function get_product($id)
    {
        return Product::active()->with(['rating', 'seller.shop','tags'])->where('id', $id)->first();
    }

    public static function get_latest_products($limit = 10, $offset = 1)
    {
        $paginator = Product::active()->with(['rating','tags','seller.shop'])->latest()->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_featured_products($limit = 10, $offset = 1)
    {
        //change review to ratting
        $paginator = Product::with(['rating','tags'])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_top_rated_products($limit = 10, $offset = 1)
    {
        //change review to ratting
        $reviews = Product::with(['rating','tags'])->active()
            ->withCount(['reviews'])->orderBy('reviews_count', 'DESC')
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $reviews->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $reviews
        ];
    }

    public static function get_best_selling_products($limit = 10, $offset = 1)
    {
        //change reviews to rattings
        $paginator = OrderDetail::with('product.rating')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $data = [];
        foreach ($paginator as $order) {
            array_push($data, $order->product);
        }

        return [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $data
        ];
    }

    public static function get_related_products($product_id)
    {
        $product = Product::find($product_id);
        return Product::active()->with(['rating','tags'])->where('category_ids', $product->category_ids)
            ->where('id', '!=', $product->id)
            ->limit(10)
            ->get();
    }

    public static function search_products($name, $category='all', $limit = 10, $offset = 1)
    {
        /*$key = explode(' ', $name);*/
        $key = [base64_decode($name)];

        $paginator = Product::active()->with(['rating','tags'])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%")
                ->orWhereHas('tags',function($query)use($key){
                    $query->where(function($q)use($key){
                        foreach ($key as $value) {
                            $q->where('tag', 'like', "%{$value}%");
                        }
                    });
                });
            }
        });

        if (isset($category) && $category != 'all') {
            $products = $paginator->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category_id) {
                    if ($category_id['id'] == $category) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $paginator->whereIn('id', $product_ids);
        }else{
            $query = $paginator;
        }

        $fetched = $query->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $fetched->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $fetched->items()
        ];
    }
    public static function search_products_web($name, $category='all', $limit = 10, $offset = 1)
    {

        $key = explode(' ', $name);
        $paginator = Product::active()->with(['rating','tags'])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%")
                    ->orWhereHas('tags',function($query)use($value){
                        $query->where('tag', 'like', "%{$value}%");
                    });
            }
        });

        if (isset($category) && $category != 'all') {
            $products = $paginator->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category_id) {
                    if ($category_id['id'] == $category) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $paginator->whereIn('id', $product_ids);
        }else{
            $query = $paginator;
        }

        $fetched = $query->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $fetched->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $fetched->items()
        ];
    }

    public static function translated_product_search($name, $category='all', $limit = 10, $offset = 1)
    {
        $name = base64_decode($name);
        $product_ids = Translation::where('translationable_type', 'App\Model\Product')
            ->where('key', 'name')
            ->where('value', 'like', "%{$name}%")
            ->pluck('translationable_id');

        $paginator = Product::with('tags')
            ->WhereIn('id', $product_ids);

        $query = $paginator;
        if ($category != 'all') {
            $products = $paginator->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category_id) {
                    if ($category_id['id'] == $category) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $paginator->whereIn('id', $product_ids);
        }

        $fetched = $query->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $fetched->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $fetched->items()
        ];
    }

    public static function translated_product_search_web($name, $category='all', $limit = 10, $offset = 1)
    {
        $key = explode(' ', $name);
        $product_ids = Translation::where('translationable_type', 'App\Model\Product')
            ->where('key', 'name')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('value', 'like', "%{$value}%");
                }
            })
            ->pluck('translationable_id');

        $paginator = Product::with('tags')
            ->WhereIn('id', $product_ids);

        $query = $paginator;
        if ($category != 'all') {
            $products = $paginator->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category_id) {
                    if ($category_id['id'] == $category) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $paginator->whereIn('id', $product_ids);
        }

        $fetched = $query->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $fetched->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $fetched->items()
        ];
    }

    public static function product_image_path($image_type)
    {
        $path = '';
        if ($image_type == 'thumbnail') {
            $path = asset('storage/app/public/product/thumbnail');
        } elseif ($image_type == 'product') {
            $path = asset('storage/app/public/product');
        }
        return $path;
    }

    public static function get_product_review($id)
    {
        $reviews = Review::where('product_id', $id)
            ->where('status', 1)->get();
        return $reviews;
    }

    public static function get_rating($reviews)
    {
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;
        foreach ($reviews as $key => $review) {
            if ($review->rating == 5) {
                $rating5 += 1;
            }
            if ($review->rating == 4) {
                $rating4 += 1;
            }
            if ($review->rating == 3) {
                $rating3 += 1;
            }
            if ($review->rating == 2) {
                $rating2 += 1;
            }
            if ($review->rating == 1) {
                $rating1 += 1;
            }
        }
        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }

    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }

    public static function get_shipping_methods($product)
    {
        if ($product['added_by'] == 'seller') {
            $methods = ShippingMethod::where(['creator_id' => $product['user_id']])->where(['status' => 1])->get();
            if ($methods->count() == 0) {
                $methods = ShippingMethod::where(['creator_type' => 'admin'])->where(['status' => 1])->get();
            }
        } else {
            $methods = ShippingMethod::where(['creator_type' => 'admin'])->where(['status' => 1])->get();
        }

        return $methods;
    }

    public static function get_seller_products($seller_id, $request)
    {
        $limit = $request['limit'];
        $offset = $request['offset'];
        $paginator = Product::active()->with(['rating','tags'])
            ->when($request->search, function ($query) use($request){
                $key = explode(' ', $request->search);
                foreach ($key as $value) {
                    $query->where('name', 'like', "%{$value}%");
                }
            })
            ->where(['user_id' => $seller_id, 'added_by' => 'seller'])
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_seller_all_products($seller_id, $limit = 10, $offset = 1)
    {
        $paginator = Product::with(['rating','tags'])
            ->where(['user_id' => $seller_id, 'added_by' => 'seller'])
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_discounted_product($limit = 10, $offset = 1)
    {
        //change review to ratting
        $paginator = Product::with(['rating','tags'])->active()->where('discount', '!=', 0)->latest()->paginate($limit, ['*'], 'page', $offset);
        return [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'products' => $paginator->items()
        ];
    }
    public static function export_product_reviews($data)
    {
        $storage = [];
        foreach ($data as $item) {
            $storage[] = [
                'product' => $item->product['name'] ?? '',
                'customer' => isset($item->customer) ? $item->customer->f_name .' '. $item->customer->l_name : '' ,
                'comment' => $item->comment,
                'rating' => $item->rating
            ];
        }
        return $storage;
    }

//    public static function get_wishlist_status($data)
//    {
//        $wishlist = Wishlist::where('customer_id', Auth::guard('customer')->user()->id ?? 0)->where('product_id', $data)->first();
//        $result = $wishlist ? 1 : 0;
//        return $result;
//    }
//    public static function get_compare_list_status($data)
//    {
//        $wishlist = ProductCompare::where('user_id', Auth::guard('customer')->user()->id ?? 0)->where('product_id', $data)->first();
//        $result = $wishlist ? 1 : 0;
//        return $result;
//    }

    public static function get_user_total_product($added_by, $user_id)
    {
        $total_product = Product::active()->where(['added_by'=>$added_by, 'user_id'=>$user_id])->count();
        return $total_product;
    }

    public static function get_products_rating_quantity($products)
    {
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;

        foreach ($products as $product)
        {
            $review = Review::where(['product_id'=>$product])->avg('rating');
            if($review == 5)
            {
                $rating5 += 1;
            }else if($review >= 4 && $review < 5)
            {
                $rating4 += 1;
            }else if($review >= 3 && $review < 4)
            {
                $rating3 += 1;
            }else if($review >= 2 && $review < 3)
            {
                $rating2 += 1;
            }else if($review >= 1 && $review < 2)
            {
                $rating1 += 1;
            }
        }

        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }

    public static function get_products_delivery_charge($product, $quantity)
    {
        $delivery_cost = 0;
        $shipping_model = Helpers::get_business_settings('shipping_method');
        $shipping_type = "";

        if($shipping_model == "inhouse_shipping"){
            $shipping_type = ShippingType::where(['seller_id'=>0])->first();
            if($shipping_type->shipping_type == "category_wise"){
                $cat_id = $product->category_id;
                $CategoryShippingCost = CategoryShippingCost::where(['seller_id'=>0,'category_id'=>$cat_id])->first();
                $delivery_cost = $CategoryShippingCost ?
                        ($CategoryShippingCost->multiply_qty != 0 ? ($CategoryShippingCost->cost * $quantity) : $CategoryShippingCost->cost)
                    : 0;

            }elseif($shipping_type->shipping_type == "product_wise"){
                $delivery_cost = $product->multiply_qty != 0 ? ($product->shipping_cost * $quantity) : $product->shipping_cost;
            }
        }elseif($shipping_model == "sellerwise_shipping"){

            if($product->added_by == "admin")
            {
                $shipping_type = ShippingType::where('seller_id','=',0)->first();
            }else{
                $shipping_type = ShippingType::where('seller_id','!=',0)->where(['seller_id'=>$product->user_id])->first();
            }
            if($shipping_type)
            {
                $shipping_type = $shipping_type ?? ShippingType::where('seller_id','=',0)->first();
                if($shipping_type->shipping_type == "category_wise"){
                    $cat_id = $product->category_id;
                    if($product->added_by == "admin")
                    {
                        $CategoryShippingCost = CategoryShippingCost::where(['seller_id'=>0,'category_id'=>$cat_id])->first();
                    }else{
                        $CategoryShippingCost = CategoryShippingCost::where(['seller_id'=>$product->user_id,'category_id'=>$cat_id])->first();
                    }

                    $delivery_cost = $CategoryShippingCost ?
                        ($CategoryShippingCost->multiply_qty != 0 ? ($CategoryShippingCost->cost * $quantity) : $CategoryShippingCost->cost)
                    : 0;
                }elseif($shipping_type->shipping_type == "product_wise"){
                    $delivery_cost = $product->multiply_qty != 0 ? ($product->shipping_cost * $quantity) : $product->shipping_cost;
                }
            }
        }
        $data = [
            'delivery_cost'=>$delivery_cost,
            'shipping_type'=>$shipping_type->shipping_type ?? '',
        ];
        return $data;
    }

    public static function get_colors_form_products()
    {
        $colors_merge = [];

        $colors_collection = Product::active()
                                        ->where('colors', '!=', '[]')
                                        ->pluck('colors')
                                        ->unique()
                                        ->toArray();

        foreach ($colors_collection as $color_json) {
            $color_array = json_decode($color_json, true);
            $colors_merge = array_merge($colors_merge, $color_array);
        }
        $colors = array_unique($colors_merge);

        return $colors;
    }
}
