<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Brand;
use App\Model\Category;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\Translation;
use App\Model\Wishlist;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class ProductListController extends Controller
{
    public function products(Request $request)
    {
        $theme_name = theme_root_path();

        return match ($theme_name){
            'default' => self::default_theme($request),
            'theme_aster' => self::theme_aster($request),
            'theme_fashion' => self::theme_fashion($request),
            'theme_all_purpose' => self::theme_all_purpose($request),
        };
    }

    public function default_theme($request){
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

        if (!$request->has('data_from') || $request['data_from'] == 'latest') {
            $query = $porduct_data;
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

        if ($request['data_from'] == 'featured_deal') {
            $featured_deal_id = FlashDeal::where(['status'=>1])->where(['deal_type'=>'feature_deal'])->pluck('id')->first();
            $featured_deal_product_ids = FlashDealProduct::where('flash_deal_id',$featured_deal_id)->pluck('product_id')->toArray();
            $query = Product::with(['reviews'])->active()->whereIn('id', $featured_deal_product_ids);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $product_ids = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhereHas('tags',function($query)use($value){
                            $query->where('tag', 'like', "%{$value}%");
                        });
                }
            })->pluck('id');

            if($product_ids->count()==0)
            {
                $product_ids = Translation::where('translationable_type', 'App\Model\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');


            }

            $query = $porduct_data->WhereIn('id', $product_ids);

        }

        if ($request['data_from'] == 'discounted') {
            $query = Product::with(['reviews'])->active()->where('discount', '!=', 0);
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query->latest();
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

        $products = $fetched->paginate(20)->appends($data);

        if ($request->ajax()) {

            return response()->json([
                'total_product'=>$products->total(),
                'view' => view('web-views.products._ajax-products', compact('products'))->render()
            ], 200);
        }
        if ($request['data_from'] == 'category') {
            $data['brand_name'] = Category::find((int)$request['id'])->name;
        }
        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if($brand_data) {
                $data['brand_name'] = $brand_data->name;
            }else {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }

        return view(VIEW_FILE_NAMES['products_view_page'], compact('products', 'data'));
    }

    public function theme_aster($request){
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with([
            'reviews','rating',
            'seller.shop',
            'wish_list'=>function($query){
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            },
            'compare_list'=>function($query){
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }
        ]);

        $product_ids = [];
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

        if ($request->has('search_category_value') && $request['search_category_value'] != 'all') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['search_category_value']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
        }

        if (!$request->has('data_from') || $request['data_from'] == 'latest') {
            $query = $porduct_data;
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
            $query = Product::with([
                'reviews','seller.shop',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->where('featured', 1);
        }

        if ($request['data_from'] == 'featured_deal') {
            $featured_deal_id = FlashDeal::where(['status'=>1])->where(['deal_type'=>'feature_deal'])->pluck('id')->first();
            $featured_deal_product_ids = FlashDealProduct::where('flash_deal_id',$featured_deal_id)->pluck('product_id')->toArray();
            $query = Product::with([
                'reviews','seller.shop',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->whereIn('id', $featured_deal_product_ids);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
                $product_ids = Product::with([
                    'seller.shop',
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%")
                            ->orWhereHas('tags',function($query)use($value){
                                $query->where('tag', 'like', "%{$value}%");
                            });
                    }
                })->pluck('id');

            if($product_ids->count()==0)
            {
                $product_ids = Translation::where('translationable_type', 'App\Model\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }

            $query = $porduct_data->WhereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'discounted') {
            $query = Product::with([
                'reviews','seller.shop',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->where('discount', '!=', 0);
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query->latest();
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        if ($request['ratings'] != null)
        {
            $fetched->with('rating')->whereHas('rating', function($query) use($request){
                return $query;
            });
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
        $common_query = $fetched;

        $rating_1 = 0;
        $rating_2 = 0;
        $rating_3 = 0;
        $rating_4 = 0;
        $rating_5 = 0;

        foreach($common_query->get() as $rating){
            if(isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] >0 && $rating->rating[0]['average'] <2)){
                $rating_1 += 1;
            }elseif(isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] >=2 && $rating->rating[0]['average'] <3)){
                $rating_2 += 1;
            }elseif(isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] >=3 && $rating->rating[0]['average'] <4)){
                $rating_3 += 1;
            }elseif(isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] >=4 && $rating->rating[0]['average'] <5)){
                $rating_4 += 1;
            }elseif(isset($rating->rating[0]['average']) && ($rating->rating[0]['average'] == 5)){
                $rating_5 += 1;
            }
        }
        $ratings = [
            'rating_1'=>$rating_1,
            'rating_2'=>$rating_2,
            'rating_3'=>$rating_3,
            'rating_4'=>$rating_4,
            'rating_5'=>$rating_5,
        ];

        $products = $common_query->paginate(20)->appends($data);

        if ($request['ratings'] != null)
        {
            $products = $products->map(function($product) use($request){
                $product->rating = $product->rating->pluck('average')[0];
                return $product;
            });
            $products = $products->where('rating','>=',$request['ratings'])
                ->where('rating','<',$request['ratings']+1)
                ->paginate(20)->appends($data);
        }

        if ($request->ajax()) {
            return response()->json([
                'total_product'=>$products->total(),
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products','product_ids'))->render(),
            ], 200);
        }
        if ($request['data_from'] == 'category') {
            $data['brand_name'] = Category::find((int)$request['id'])->name;
        }
        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if($brand_data) {
                $data['brand_name'] = $brand_data->name;
            }else {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }

        return view(VIEW_FILE_NAMES['products_view_page'], compact('products', 'data', 'ratings', 'product_ids'));
    }

    public function theme_fashion(Request $request)
    {
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with(['reviews','rating','wish_list'=>function($query){
                            return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                        },
                        'compare_list'=>function($query){
                            return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                        }]);

        $product_ids = [];
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

        if ($request->has('search_category_value') && $request['search_category_value'] != 'all') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['search_category_value']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
        }

        if (!$request->has('data_from') || $request['data_from'] == 'latest') {
            $query = $porduct_data;
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

        if ($request['data_from'] == 'featured_deal') {
            $featured_deal_id = FlashDeal::where(['status'=>1])->where(['deal_type'=>'feature_deal'])->pluck('id')->first();
            $featured_deal_product_ids = FlashDealProduct::where('flash_deal_id',$featured_deal_id)->pluck('product_id')->toArray();
            $query = Product::with(['reviews'])->active()->whereIn('id', $featured_deal_product_ids);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $product_ids = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhereHas('tags',function($query)use($value){
                            $query->where('tag', 'like', "%{$value}%");
                        });
                }
            })->pluck('id');

            if($product_ids->count()==0)
            {
                $product_ids = Translation::where('translationable_type', 'App\Model\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }

            $query = $porduct_data->WhereIn('id', $product_ids);

        }

        if ($request['data_from'] == 'discounted') {
            $query = Product::with(['reviews'])->active()->where('discount', '!=', 0);
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query->latest();
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }
        $common_query = $fetched;

        $products = $common_query->paginate(20);

        if ($request['ratings'] != null)
        {
            $products = $products->map(function($product) use($request){
                $product->rating = $product->rating->pluck('average')[0];
                return $product;
            });
            $products = $products->where('rating','>=',$request['ratings'])
                ->where('rating','<',$request['ratings']+1)
                ->paginate(20);
        }

        // Categories start
        $categories = [];
        $category_info_for_fashion = Category::where('position',0)->pluck('id');
        foreach ($category_info_for_fashion as $category_id) {
            $category = Category::withCount(['product'=>function($qc1){
                $qc1->where(['status'=>'1']);
            }])->with(['childes' => function ($qc2) {
                $qc2->with(['childes' => function ($qc3) {
                    $qc3->withCount(['sub_sub_category_product'])->where('position', 2);
                }])->withCount(['sub_category_product'])->where('position', 1);
            }, 'childes.childes'])
                ->where('position', 0)
                ->find($category_id);

            if ($category != null) {
                array_push($categories, $category);
            }
        }
        $categories = array_unique($categories);
        // Categories End

        // Colors Start
        $colors_in_shop_merge = [];
        $colors_collection = Product::active()
            ->where('colors', '!=', '[]')
            ->pluck('colors')
            ->unique()
            ->toArray();

        foreach ($colors_collection as $color_json) {
            $color_array = json_decode($color_json, true);
            $colors_in_shop_merge = array_merge($colors_in_shop_merge, $color_array);
        }
        $colors_in_shop = array_unique($colors_in_shop_merge);
        // Colors End
        $banner = \App\Model\BusinessSetting::where('type', 'banner_product_list_page')->whereJsonContains('value', ['status' => '1'])->first();

        if ($request->ajax()) {
            return response()->json([
                'total_product'=>$products->total(),
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products','product_ids'))->render(),
            ], 200);
        }

        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if(!$brand_data) {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }

        return view(VIEW_FILE_NAMES['products_view_page'], compact('products',  'product_ids','categories','colors_in_shop','banner'));
    }

    public function theme_all_purpose(Request $request)
    {
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with(['reviews','rating']);

        $product_ids = [];
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

        if ($request->has('search_category_value') && $request['search_category_value'] != 'all') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['search_category_value']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
        }

        if (!$request->has('data_from') || $request['data_from'] == 'latest') {
            $query = $porduct_data;
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

        if ($request['data_from'] == 'featured_deal') {
            $featured_deal_id = FlashDeal::where(['status'=>1])->where(['deal_type'=>'feature_deal'])->pluck('id')->first();
            $featured_deal_product_ids = FlashDealProduct::where('flash_deal_id',$featured_deal_id)->pluck('product_id')->toArray();
            $query = Product::with(['reviews'])->active()->whereIn('id', $featured_deal_product_ids);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $product_ids = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhereHas('tags',function($query)use($value){
                            $query->where('tag', 'like', "%{$value}%");
                        });
                }
            })->pluck('id');

            if($product_ids->count()==0)
            {
                $product_ids = Translation::where('translationable_type', 'App\Model\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }

            $query = $porduct_data->WhereIn('id', $product_ids);

        }

        if ($request['data_from'] == 'discounted') {
            $query = Product::with(['reviews'])->active()->where('discount', '!=', 0);
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query->latest();
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }
        $common_query = $fetched;

        $products = $common_query->paginate(20);

        if ($request['ratings'] != null)
        {
            $products = $products->map(function($product) use($request){
                $product->rating = $product->rating->pluck('average')[0];
                return $product;
            });
            $products = $products->where('rating','>=',$request['ratings'])
                ->where('rating','<',$request['ratings']+1)
                ->paginate(20);
        }

        // Categories start
        $categories = [];
        $category_info_for_fashion = Category::where('position',0)->pluck('id');
        foreach ($category_info_for_fashion as $category_id) {
            $category = Category::withCount(['product'=>function($qc1){
                $qc1->where(['status'=>'1']);
            }])->with(['childes' => function ($qc2) {
                $qc2->with(['childes' => function ($qc3) {
                    $qc3->withCount(['sub_sub_category_product'])->where('position', 2);
                }])->withCount(['sub_category_product'])->where('position', 1);
            }, 'childes.childes'])
                ->where('position', 0)
                ->find($category_id);

            if ($category != null) {
                array_push($categories, $category);
            }
        }
        $categories = array_unique($categories);
        // Categories End

        // Colors Start
        $colors_in_shop_merge = [];
        $colors_collection = Product::active()
            ->where('colors', '!=', '[]')
            ->pluck('colors')
            ->unique()
            ->toArray();

        foreach ($colors_collection as $color_json) {
            $color_array = json_decode($color_json, true);
            $colors_in_shop_merge = array_merge($colors_in_shop_merge, $color_array);
        }
        $colors_in_shop = array_unique($colors_in_shop_merge);
        // Colors End
        $banner = \App\Model\BusinessSetting::where('type', 'banner_product_list_page')->whereJsonContains('value', ['status' => '1'])->first();

        if ($request->ajax()) {
            return response()->json([
                'total_product'=>$products->total(),
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products','product_ids'))->render(),
            ], 200);
        }

        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if(!$brand_data) {
                Toastr::warning(translate('not_found'));
                return redirect('/');
            }
        }
        return view(VIEW_FILE_NAMES['products_view_page'], compact('products',  'product_ids','categories','colors_in_shop','banner'));
    }
}
