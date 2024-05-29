<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use App\Model\Brand;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Coupon;
use App\Model\DealOfTheDay;
use App\Model\FlashDeal;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\Stat;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    public function __construct(
        private Product      $product,
        private Order        $order,
        private OrderDetail  $order_details,
        private Category     $category,
        private Seller       $seller,
        private Review       $review,
        private DealOfTheDay $deal_of_the_day,
        private Banner       $banner,
    )
    {
    }


    public function index()
    {
        $theme_name = theme_root_path();

        return match ($theme_name) {
            'default' => self::default_theme(),
            'theme_aster' => self::theme_aster(),
            'theme_fashion' => self::theme_fashion(),
            'theme_all_purpose' => self::theme_all_purpose(),
        };
    }

    public function default_theme()
    {
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        $home_categories = Category::where('home_status', true)->priority()->get();
        $home_categories->map(function ($data) {
            $id = '"' . $data['id'] . '"';
            $data['products'] = Product::active()
                ->where('category_ids', 'like', "%{$id}%")
                ->inRandomOrder()->take(12)->get();
        });
        //products based on top seller
        $top_sellers = Seller::approved()->with('shop')
            ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(12)->get();
        //end

        //feature products finding based on selling
        $featured_products = Product::with(['reviews'])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(12)
            ->get();
        //end

        $latest_products = Product::with(['reviews'])->active()->orderBy('id', 'desc')->take(8)->get();
        $categories = Category::with('childes.childes')->where(['position' => 0])->priority()->take(11)->get();
        $brands = Brand::active()->take(15)->get();
        //best sell product
        $bestSellProduct = OrderDetail::with('product.reviews')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(4)
            ->get();

        //Top-rated
        $topRated = Review::with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(4)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }

        $deal_of_the_day = DealOfTheDay::join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();
        $main_banner = Banner::where('banner_type', 'Main Banner')->where('published', 1)->latest()->get();
        $main_section_banner = \App\Model\Banner::where('banner_type', 'Main Section Banner')->where('published', 1)->orderBy('id', 'desc')->latest()->first();

        return view(VIEW_FILE_NAMES['home'],
            compact(
                'featured_products', 'topRated', 'bestSellProduct', 'latest_products', 'categories', 'brands',
                'deal_of_the_day', 'top_sellers', 'home_categories', 'brand_setting', 'main_banner', 'main_section_banner'
            )
        );
    }

    public function theme_aster()
    {
        if (!Session::has('visited_home')) {
            DB::table('stats')->where('id', 1)->increment('viewers');
            Session::put('visited_home', true);
        }   
        $current_date = date('Y-m-d H:i:s');
        $stats = Stat::find(1);
        
        $home_categories = $this->category
            ->where('home_status', true)
            ->priority()->get();

        $home_categories->map(function ($data) {
            $current_date = date('Y-m-d H:i:s');
            $data['products'] = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])
                ->where('category_id',$data['id'])
                ->inRandomOrder()->take(12)->get();

            //for flash deal
            $data['products']?->map(function ($product) use ($current_date) {
                $flash_deal_status = 0;
                if (count($product->flash_deal_product) > 0) {
                    $flash_deal = $product->flash_deal_product[0]->flash_deal;
                    if ($flash_deal) {
                        $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                        $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                        $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    }
                }
                $product['flash_deal_status'] = $flash_deal_status;
                return $product;
            });
        });

        //products based on top seller
        $top_sellers = $this->seller->approved()->with(['shop', 'coupon', 'product' => function ($query) {
            $query->where('added_by', 'seller')->active();
        }])
        ->whereHas('product', function ($query) {
            $query->where('added_by', 'seller')->active();
        })
        ->withCount(['product' => function ($query) {
            $query->active();
        }])
        ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(12)->get();

        $top_sellers->map(function ($seller) {
            $rating = 0;
            $count = 0;
            foreach ($seller->product as $item) {
                foreach ($item->reviews as $review) {
                    $rating += $review->rating;
                    $count++;
                }
            }
            $avg_rating = $rating / ($count == 0 ? 1 : $count);
            $rating_count = $count;
            $seller['average_rating'] = $avg_rating;
            $seller['rating_count'] = $rating_count;

            $product_count = $seller->product->count();
            $random_product = Arr::random($seller->product->toArray(), $product_count < 3 ? $product_count : 3);
            $seller['product'] = $random_product;
            return $seller;
        });
        //end
        
        
        // Special sellers with the best offer 
        $special_sellers = $this->seller->approved()
        ->whereHas('shop', function ($query) {
            $query->where('address', 'PRO 3300DA');
        })
        ->with(['shop', 'coupon', 'product' => function ($query) {
            $query->where('added_by', 'seller')->active();
        }])
        ->whereHas('product', function ($query) {
            $query->where('added_by', 'seller')->active();
        })
        ->withCount(['product' => function ($query) {
            $query->active();
        }])
        ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(12)->get();

        $special_sellers->map(function ($seller) {
           $rating = 0;
            $count = 0;
            foreach ($seller->product as $item) {
                foreach ($item->reviews as $review) {
                    $rating += $review->rating;
                    $count++;
                }
            }
            $avg_rating = $count == 0 ? 0 : $rating / $count;
            $rating_count = $count;
            $seller['average_rating'] = $avg_rating;
            $seller['rating_count'] = $rating_count;
    
            $product_count = $seller->product->count();
            $random_product = Arr::random($seller->product->toArray(), $product_count < 3 ? $product_count : 3);
            $seller['product'] = $random_product;
            return $seller;
        });
        
        $flash_deals = FlashDeal::with(['products'=>function($query){
                $query->with(['product.wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                }, 'product.compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }])->whereHas('product',function($q){
                    $q->active();
                });
            }])
            ->where(['deal_type'=>'flash_deal', 'status'=>1])
            ->whereDate('start_date','<=',date('Y-m-d'))
            ->whereDate('end_date','>=',date('Y-m-d'))
            ->first();

        //find what you need
        $find_what_you_need_categories = $this->category->where('parent_id', 0)
            ->with(['childes' => function ($query) {
                $query->withCount(['sub_category_product' => function ($query) {
                    return $query->active();
                }]);
            }])
            ->withCount(['product' => function ($query) {
                return $query->active();
            }])
            ->get()->toArray();

        $get_categories = [];
        foreach($find_what_you_need_categories as $category){
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $get_categories[] = $category;
        }

        $final_category = [];
        foreach ($get_categories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }
        $category_slider = array_chunk($final_category, 4);
        // end find  what you need

        // more stores
        $more_seller = $this->seller->approved()->with(['shop', 'product.reviews'])
            ->withCount(['product' => function ($query) {
                $query->active();
            }])
            ->inRandomOrder()
            ->take(20)->get();
        //end more stores

        //feature products finding based on selling
        $featured_products = $this->product->with([
                'seller.shop',
                'flash_deal_product.flash_deal',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(10)
            ->get();

        $featured_products?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flash_deal_product) > 0) {
                $flash_deal = $product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
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
        //end

        //latest product
        $latest_products = $this->product->with([
                'seller.shop',
                'flash_deal_product.flash_deal',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->active()->orderBy('id', 'desc')
            ->take(15)
            ->get();
        $latest_products?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flash_deal_product) > 0) {
                $flash_deal = $product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
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
        //end latest product

        //featured deal product start
        $featured_deals = Product::active()
            ->with([
                'seller.shop',
                'flash_deal_product.feature_deal',
                'flash_deal_product.flash_deal' => function($query){
                    return $query->whereDate('start_date', '<=', date('Y-m-d'))
                        ->whereDate('end_date', '>=', date('Y-m-d'));
                },
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->whereHas('flash_deal_product.feature_deal', function($query){
                $query->whereDate('start_date', '<=', date('Y-m-d'))
                    ->whereDate('end_date', '>=', date('Y-m-d'));
            })
            ->get();

        if($featured_deals){
            foreach($featured_deals as $product){
                $flash_deal_status = 0;
                $flash_deal_end_date = 0;

                foreach($product->flash_deal_product as $deal){
                    $flash_deal_status = $deal->flash_deal ? 1 : $flash_deal_status;
                    $flash_deal_end_date = isset($deal->flash_deal->end_date) ? date('Y-m-d H:i:s', strtotime($deal->flash_deal->end_date)) : $flash_deal_end_date;
                }

                $product['flash_deal_status'] = $flash_deal_status;
                $product['flash_deal_end_date'] = $flash_deal_end_date;
            }
        }
        //featured deal product end

        //best sell product
        $bestSellProduct = $this->order_details->with([
                'product.reviews',
                'product.flash_deal_product.flash_deal',
                'product.seller.shop',
                'product.wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'product.compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(10)
            ->get();

        $bestSellProduct?->map(function ($order) use ($current_date) {
            if(!isset($order->product)){
                return $order;
            }
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (isset($order->product->flash_deal_product) && count($order->product->flash_deal_product) > 0) {
                $flash_deal = $order->product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $order->product['flash_deal_status'] = $flash_deal_status;
            $order->product['flash_deal_end_date'] = $flash_deal_end_date;
            return $order;
        });

        // Just for you portion
        if (auth('customer')->check()) {
            $orders = $this->order->where(['customer_id' => auth('customer')->id()])->with(['details'])->get();

            if ($orders) {
                $orders = $orders?->map(function ($order) {
                    $order_details = $order->details->map(function ($detail) {
                        $product = json_decode($detail->product_details);
                        $category = json_decode($product->category_ids)[0]->id;
                        $detail['category_id'] = $category;
                        return $detail;
                    });
                    $order['id'] = $order_details[0]->id;
                    $order['category_id'] = $order_details[0]->category_id;

                    return $order;
                });

                $categories = [];
                foreach ($orders as $order) {
                    $categories[] = ($order['category_id']);;
                }
                $ids = array_unique($categories);


                $just_for_you = $this->product->with([
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()
                ->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhere('category_ids', 'like', "%{$id}%");
                    }
                })
                ->inRandomOrder()
                ->take(8)
                ->get();
            } else {
                $just_for_you = $this->product->with([
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()->inRandomOrder()->take(8)->get();
            }
        } else {
            $just_for_you = $this->product->with([
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->inRandomOrder()->take(8)->get();
        }
        // end just for you

        $topRated = $this->review->with([
                'product.seller.shop',
                'product.wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'product.compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(10)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }
        $mostViewed = Product::active()
            ->with([
                'seller.shop',
                'flash_deal_product.flash_deal',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();
            
        $mostViewed?->map(function ($product) use ($current_date) {
        $flash_deal_status = 0;
        $flash_deal_end_date = 0;
        if (count($product->flash_deal_product) > 0) {
            $flash_deal = $product->flash_deal_product[0]->flash_deal;
            if ($flash_deal) {
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


        $deal_of_the_day = $this->deal_of_the_day->join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();
        $random_product = $this->product->active()->inRandomOrder()->first();

        $banner_list = ['Main Banner', 'Footer Banner', 'Sidebar Banner', 'Main Section Banner', 'Top Side Banner'];
        $banners = $this->banner->whereIn('banner_type', $banner_list)->where('published', 1)->orderBy('id', 'desc')->latest('created_at')->get();

        $main_banner = [];
        $footer_banner = [];
        $sidebar_banner = [];
        $main_section_banner = [];
        $top_side_banner = [];
        foreach($banners as $banner){
            if($banner->banner_type == 'Main Banner'){
                $main_banner[] = $banner;
            }elseif($banner->banner_type == 'Footer Banner'){
                $footer_banner[] = $banner->toArray();
            }elseif($banner->banner_type == 'Sidebar Banner'){
                $sidebar_banner[] = $banner;
            }elseif($banner->banner_type == 'Main Section Banner'){
                $main_section_banner[] = $banner;
            }elseif($banner->banner_type == 'Top Side Banner'){
                $top_side_banner[] = $banner;
            }
        }
        $sidebar_banner = $sidebar_banner ? $sidebar_banner[0] : [];
        $main_section_banner = $main_section_banner ? $main_section_banner[0] : [];
        $top_side_banner = $top_side_banner ? $top_side_banner[0] : [];
        $footer_banner = $footer_banner ? array_slice($footer_banner, 0, 2):[];

        $decimal_point = Helpers::get_business_settings('decimal_point_settings');
        $decimal_point_settings = !empty($decimal_point) ? $decimal_point : 0;
        $user = Helpers::get_customer();
        $categories = Category::with('childes.childes')->where(['position' => 0])->priority()->take(11)->get();

        //order again
        $order_again = $user != 'offline' ?
            $this->order->with('details.product')->where(['order_status' => 'delivered', 'customer_id' => $user->id])->latest()->take(8)->get()
            : [];

        $random_coupon = Coupon::with('seller')
            ->where(['status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->inRandomOrder()->take(3)->get();
        
        $computerCategory = $this->category->where('name', 'Computer')->first();
        $clothesCategory = $this->category->where('name', 'Clothes')->first();
        $cosmeticsCategory = $this->category->where('name', 'Cosmetics  Beauty')->first();
        $entertainmentCategory = $this->category->where('name', 'Entertainment')->first();
        $homeSuppliesCategory = $this->category->where('name', 'Home Supplies')->first();
        $telephonesCategory = $this->category->where('name', 'Telephones')->first();
        $phoneAccessoriesCategory = $this->category->where('name', 'Phone Accessories')->first();



        if ($computerCategory) {
            $computerProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $computerCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();

            $computerCategory['products'] = $computerProducts;
        }
        if ($clothesCategory) {
            $clothesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $clothesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $clothesCategory['products'] = $clothesProducts;
        }
        if ($cosmeticsCategory) {
            $cosmeticsProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $cosmeticsCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $cosmeticsCategory['products'] = $cosmeticsProducts;
        }
        if ($homeSuppliesCategory) {
            $homeSuppliesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $homeSuppliesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $homeSuppliesCategory['products'] = $homeSuppliesProducts;
        }
        if ($entertainmentCategory) {
            $entertainmentProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $entertainmentCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $entertainmentCategory['products'] = $entertainmentProducts;
        }
        if ($telephonesCategory) {
            $telephonesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $telephonesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $telephonesCategory['products'] = $telephonesProducts;
        }
           if ($phoneAccessoriesCategory) {
            $phoneAccessoriesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $phoneAccessoriesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $phoneAccessoriesCategory['products'] = $phoneAccessoriesProducts;
        }
        
    
        
        
       
        return view(VIEW_FILE_NAMES['home'],
            compact(
                'topRated', 'bestSellProduct', 'latest_products', 'featured_products', 'deal_of_the_day', 'top_sellers',
                'home_categories', 'main_banner', 'footer_banner', 'random_product', 'decimal_point_settings', 'just_for_you', 'more_seller',
                'final_category', 'category_slider', 'order_again', 'sidebar_banner', 'main_section_banner', 'random_coupon', 'top_side_banner','stats',
                'featured_deals', 'flash_deals', 'categories' , 'special_sellers','mostViewed','computerProducts','clothesProducts','cosmeticsProducts','homeSuppliesProducts','entertainmentProducts','telephonesProducts','phoneAccessoriesProducts'
            )
        );
        
    }

    public function theme_fashion()
    {
        $current_date = date('Y-m-d H:i:s');

        $main_banner = Banner::where('banner_type', 'Main Banner')->where('published', 1)->latest()->get();
        $promo_banner = Banner::where('banner_type', 'Promo Banner')->where('published', 1)->take(5)->get();
        $mega_sell_banner = Banner::where('banner_type', 'Mega Sell Banner')->where('published', 1)->first();

        //products based on top seller
        $top_sellers = Seller::approved()->with(['shop', 'coupon', 'product' => function ($query) {
            $query->where('added_by', 'seller')->active();
        }])
            ->whereHas('product', function ($query) {
                $query->where('added_by', 'seller')->active();
            })
            ->withCount(['product' => function ($query) {
                $query->active();
            }])
            ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(12)->get();

        $top_sellers->map(function ($seller) {
            $product_ids = $seller->product->pluck('id');
            $rating = Review::whereIn('product_id', $product_ids);
            $avg_rating = $rating->avg('rating');
            $rating_count = $rating->count();
            $seller['average_rating'] = $avg_rating;
            $seller['rating_count'] = $rating_count;

            $product_count = $seller->product->count();
            $random_product = Arr::random($seller->product->toArray(), $product_count < 3 ? $product_count : 3);
            $seller['product'] = $random_product;
            return $seller;
        });
        //end products based on top seller

        // more stores
        $more_seller = Seller::approved()->with(['shop', 'product.reviews'])
            ->withCount(['product' => function ($query) {
                $query->active();
            }])
            ->inRandomOrder()
            ->take(7)->get();

        // new stores
        $new_seller = Seller::approved()->with(['shop', 'product.reviews'])
            ->withCount(['product' => function ($query) {
                $query->active();
            }])
            ->latest()
            ->take(7)->get();

            $more_seller = $more_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach ($product->reviews as $reviews) {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });

            $new_seller = $new_seller->map(function ($seller) {
                $review_count = 0;
                $rating = [];
                foreach ($seller->product as $product) {
                    $review_count += $product->reviews_count;
                    foreach ($product->reviews as $reviews) {
                        $rating[] = $reviews['rating'];
                    }
                }
                $seller['reviews_count'] = $review_count;
                $seller['rating'] = collect($rating)->average() ?? 0;
                return $seller;
            });
        //end more stores

        //latest product
        $latest_products = $this->product->with(['reviews', 'flash_deal_product.flash_deal','wish_list'=>function($query){
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            }])
            ->active()->orderBy('id', 'desc')
            ->take(15)
            ->get();
        $latest_products?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flash_deal_product) > 0) {
                $flash_deal = $product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
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
        //end latest product

        //best sell product
        $bestSellProduct = $this->order_details->with(['product.reviews', 'product.flash_deal_product.flash_deal','product.wish_list'=>function($query){
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            }])
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(10)
            ->get();

        $bestSellProduct?->map(function ($order) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($order->product->flash_deal_product) > 0) {
                $flash_deal = $order->product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $order->product['flash_deal_status'] = $flash_deal_status;
            $order->product['flash_deal_end_date'] = $flash_deal_end_date;
            return $order;
        });
        //end best sell product

        $deal_of_the_day = DealOfTheDay::join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();
        $random_product = \App\Model\Product::active()->inRandomOrder()->first();

        $main_banner = Banner::where('banner_type', 'Main Banner')->where('published', 1)->latest()->get();

        $footer_banner = Banner::where('banner_type', 'Footer Banner')->where('published', 1)->latest()->take(2)->get();
        $sidebar_banner = Banner::where('banner_type', 'Sidebar Banner')->where('published', 1)->latest()->first();
        $main_section_banner = \App\Model\Banner::where('banner_type', 'Main Section Banner')->where('published', 1)->orderBy('id', 'desc')->latest()->first();
        $top_side_banner = \App\Model\Banner::where('banner_type', 'Top Side Banner')->where('published', 1)->orderBy('id', 'desc')->latest()->first();

        $decimal_point_settings = !empty(\App\CPU\Helpers::get_business_settings('decimal_point_settings')) ? \App\CPU\Helpers::get_business_settings('decimal_point_settings') : 0;
        $user = Helpers::get_customer();

        // theme fashion -- Shop Again From Your Recent Store
        $recent_order_shops = $user != 'offline' ?
                $this->product->with('seller.orders', 'seller.shop')
                    ->whereHas('seller.orders', function ($query) {
                        $query->where(['customer_id' => auth('customer')->id(), 'seller_is' => 'seller']);
                    })->active()
                    ->inRandomOrder()->take(12)->get()
                : [];
        //end theme fashion -- Shop Again From Your Recent Store

        $most_searching_product = Product::with(['wish_list'=>function($query){
            return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
        }])->withSum('tags', 'visit_count')->orderBy('tags_sum_visit_count', 'desc')->get();

        $category_ids = $most_searching_product->pluck('category_id')->unique();

        $categories = Category::withCount(['product'=>function($qc1){
                                $qc1->where(['status'=>'1']);
                            }])->with(['childes' => function ($qc2) {
                                $qc2->with(['childes' => function ($qc3) {
                                    $qc3->withCount(['sub_sub_category_product'])->where('position', 2);
                                }])->withCount(['sub_category_product'])->where('position', 1);
                            }, 'childes.childes'])
                            ->where('position', 0)
                            ->get();

        $product_list_category = \App\CPU\CategoryManager::get_categories_with_counting();
        $colors_in_shop = \App\CPU\ProductManager::get_colors_form_products();

        $most_searching_product = $most_searching_product->take(10);

        $all_products_info = [
            'total_products' => $this->product->active()->count(),
            'total_orders' => $this->order->count(),
            'total_delivary' => $this->order_details->where('payment_status', 'paid')->count(),
            'total_reviews' => $this->review->count(),
        ];

        $most_demanded_product = OrderDetail::select('product_id', DB::raw('COUNT(*) as count'))
                                            ->with(['product'=>function($query){
                                                $query->withCount('wish_list','order_details','order_delivered');
                                            }])
                                            ->whereYear('created_at', '=', date('Y'))
                                            ->groupBy('product_id')->orderBy('count', 'desc')
                                            ->first();

        $most_demanded_product = isset($most_demanded_product)? $most_demanded_product->product : $most_demanded_product;

        // Feature products
        $featured_products = $this->product->active()->where('featured', 1)->take(4)->get();
        // dd($featured_products);
        return view(VIEW_FILE_NAMES['home'],
            compact(
                'bestSellProduct', 'latest_products', 'deal_of_the_day', 'top_sellers', 'main_banner', 'footer_banner',
                'random_product', 'decimal_point_settings', 'more_seller', 'new_seller', 'sidebar_banner', 'main_section_banner', 'top_side_banner', 'recent_order_shops',
                'categories', 'colors_in_shop', 'all_products_info', 'most_searching_product', 'most_demanded_product', 'featured_products', 'promo_banner', 'mega_sell_banner'
            )
        );
    }

    public function theme_all_purpose(){

        $main_banner = Banner::where('banner_type','Main Banner')->where('published',1)->latest()->get();
        $footer_banner = Banner::where('banner_type', 'Footer Banner')->where('published', 1)->latest()->take(2)->get();

        // Most Searching Categories Products
        $category_ids = Product::withSum('tags', 'visit_count')->orderBy('tags_sum_visit_count', 'desc')->pluck('category_id')->unique();
        $categories = Category::withCount(['product'=>function($qc1){
            $qc1->where(['status'=>'1']);
        }])->whereIn('id', $category_ids)->orderBy('product_count', 'desc')->take(18)->get();

        $brands = Brand::active()->take(15)->get();
        //best sell product
        $bestSellProduct = OrderDetail::with(['product.category','product.wish_list'=>function($query){
                return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
            }, 'product.compare_list'=>function($query){
                return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
            }, 'product.reviews'])
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(8)
            ->get();


        //featured deal product start
        $featured_deals = Product::with([
            'seller.shop', 'category',
            'flash_deal_product.feature_deal',
            'flash_deal_product.flash_deal' => function($query){
                return $query->whereDate('start_date', '<=', date('Y-m-d'))
                    ->whereDate('end_date', '>=', date('Y-m-d'));
                }, 'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                }, 'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
                ])->whereHas('flash_deal_product.feature_deal', function($query){
                    $query->whereDate('start_date', '<=', date('Y-m-d'))
                        ->whereDate('end_date', '>=', date('Y-m-d'));
                })->get();
        //featured deal product end

        // Just for you portion
        if (auth('customer')->check()) {
            $orders = $this->order->where(['customer_id' => auth('customer')->id()])->with(['details'])->get();

            if ($orders) {
                $orders = $orders?->map(function ($order) {
                    $order_details = $order->details->map(function ($detail) {
                        $product = json_decode($detail->product_details);
                        $category = json_decode($product->category_ids)[0]->id;
                        $detail['category_id'] = $category;
                        return $detail;
                    });
                    $order['id'] = $order_details[0]->id;
                    $order['category_id'] = $order_details[0]->category_id;

                    return $order;
                });

                $categories = [];
                foreach ($orders as $order) {
                    $categories[] = ($order['category_id']);;
                }
                $ids = array_unique($categories);


                $just_for_you = $this->product->with(['reviews','rating',
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhere('category_ids', 'like', "%{$id}%");
                    }
                })->inRandomOrder()->take(4)->get();

            } else {

                $just_for_you = $this->product->with(['reviews','rating',
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()->inRandomOrder()->take(4)->get();

            }
        } else {

            $just_for_you = $this->product->with(['reviews','rating',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->inRandomOrder()->take(4)->get();

        }
        // end just for you
        $latest_products_count = Product::active()->count();
        $latest_products = Product::with(['category'])->active()->orderBy('id', 'desc')->take(8)->get();


        return view(VIEW_FILE_NAMES['home'], compact('main_banner', 'footer_banner', 'categories', 'bestSellProduct', 'featured_deals',
                                                    'just_for_you', 'latest_products_count', 'latest_products'));
    }
    
    public function mobile_application()
    {
        session()->put('device', 'mobile');
        if (!Session::has('visited_home')) {
            DB::table('stats')->where('id', 1)->increment('viewers');
            Session::put('visited_home', true);
        }   
        
        $current_date = date('Y-m-d H:i:s');
        $stats = Stat::find(1);
        
        $home_categories = $this->category
            ->where('home_status', true)
            ->priority()->get();

        $home_categories->map(function ($data) {
            $current_date = date('Y-m-d H:i:s');
            $data['products'] = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])
                ->where('category_id',$data['id'])
                ->inRandomOrder()->take(12)->get();

            //for flash deal
            $data['products']?->map(function ($product) use ($current_date) {
                $flash_deal_status = 0;
                if (count($product->flash_deal_product) > 0) {
                    $flash_deal = $product->flash_deal_product[0]->flash_deal;
                    if ($flash_deal) {
                        $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                        $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                        $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    }
                }
                $product['flash_deal_status'] = $flash_deal_status;
                return $product;
            });
        });

        //products based on top seller
        $top_sellers = $this->seller->approved()->with(['shop', 'coupon', 'product' => function ($query) {
            $query->where('added_by', 'seller')->active();
        }])
        ->whereHas('product', function ($query) {
            $query->where('added_by', 'seller')->active();
        })
        ->withCount(['product' => function ($query) {
            $query->active();
        }])
        ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(12)->get();

        $top_sellers->map(function ($seller) {
            $rating = 0;
            $count = 0;
            foreach ($seller->product as $item) {
                foreach ($item->reviews as $review) {
                    $rating += $review->rating;
                    $count++;
                }
            }
            $avg_rating = $rating / ($count == 0 ? 1 : $count);
            $rating_count = $count;
            $seller['average_rating'] = $avg_rating;
            $seller['rating_count'] = $rating_count;

            $product_count = $seller->product->count();
            $random_product = Arr::random($seller->product->toArray(), $product_count < 3 ? $product_count : 3);
            $seller['product'] = $random_product;
            return $seller;
        });
        //end
        
        
        // Special sellers with the best offer 
        $special_sellers = $this->seller->approved()
        ->whereHas('shop', function ($query) {
            $query->where('address', 'PRO 3300DA');
        })
        ->with(['shop', 'coupon', 'product' => function ($query) {
            $query->where('added_by', 'seller')->active();
        }])
        ->whereHas('product', function ($query) {
            $query->where('added_by', 'seller')->active();
        })
        ->withCount(['product' => function ($query) {
            $query->active();
        }])
        ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(12)->get();

        $special_sellers->map(function ($seller) {
           $rating = 0;
            $count = 0;
            foreach ($seller->product as $item) {
                foreach ($item->reviews as $review) {
                    $rating += $review->rating;
                    $count++;
                }
            }
            $avg_rating = $count == 0 ? 0 : $rating / $count;
            $rating_count = $count;
            $seller['average_rating'] = $avg_rating;
            $seller['rating_count'] = $rating_count;
    
            $product_count = $seller->product->count();
            $random_product = Arr::random($seller->product->toArray(), $product_count < 3 ? $product_count : 3);
            $seller['product'] = $random_product;
            return $seller;
        });
        
        $flash_deals = FlashDeal::with(['products'=>function($query){
                $query->with(['product.wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                }, 'product.compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }])->whereHas('product',function($q){
                    $q->active();
                });
            }])
            ->where(['deal_type'=>'flash_deal', 'status'=>1])
            ->whereDate('start_date','<=',date('Y-m-d'))
            ->whereDate('end_date','>=',date('Y-m-d'))
            ->first();

        //find what you need
        $find_what_you_need_categories = $this->category->where('parent_id', 0)
            ->with(['childes' => function ($query) {
                $query->withCount(['sub_category_product' => function ($query) {
                    return $query->active();
                }]);
            }])
            ->withCount(['product' => function ($query) {
                return $query->active();
            }])
            ->get()->toArray();

        $get_categories = [];
        foreach($find_what_you_need_categories as $category){
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $get_categories[] = $category;
        }

        $final_category = [];
        foreach ($get_categories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }
        $category_slider = array_chunk($final_category, 4);
        // end find  what you need

        // more stores
        $more_seller = $this->seller->approved()->with(['shop', 'product.reviews'])
            ->withCount(['product' => function ($query) {
                $query->active();
            }])
            ->inRandomOrder()
            ->take(20)->get();
        //end more stores

        //feature products finding based on selling
        $featured_products = $this->product->with([
                'seller.shop',
                'flash_deal_product.flash_deal',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(10)
            ->get();

        $featured_products?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flash_deal_product) > 0) {
                $flash_deal = $product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
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
        //end

        //latest product
        $latest_products = $this->product->with([
                'seller.shop',
                'flash_deal_product.flash_deal',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->active()->orderBy('id', 'desc')
            ->take(15)
            ->get();
        $latest_products?->map(function ($product) use ($current_date) {
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (count($product->flash_deal_product) > 0) {
                $flash_deal = $product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
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
        //end latest product

        //featured deal product start
        $featured_deals = Product::active()
            ->with([
                'seller.shop',
                'flash_deal_product.feature_deal',
                'flash_deal_product.flash_deal' => function($query){
                    return $query->whereDate('start_date', '<=', date('Y-m-d'))
                        ->whereDate('end_date', '>=', date('Y-m-d'));
                },
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->whereHas('flash_deal_product.feature_deal', function($query){
                $query->whereDate('start_date', '<=', date('Y-m-d'))
                    ->whereDate('end_date', '>=', date('Y-m-d'));
            })
            ->get();

        if($featured_deals){
            foreach($featured_deals as $product){
                $flash_deal_status = 0;
                $flash_deal_end_date = 0;

                foreach($product->flash_deal_product as $deal){
                    $flash_deal_status = $deal->flash_deal ? 1 : $flash_deal_status;
                    $flash_deal_end_date = isset($deal->flash_deal->end_date) ? date('Y-m-d H:i:s', strtotime($deal->flash_deal->end_date)) : $flash_deal_end_date;
                }

                $product['flash_deal_status'] = $flash_deal_status;
                $product['flash_deal_end_date'] = $flash_deal_end_date;
            }
        }
        //featured deal product end

        //best sell product
        $bestSellProduct = $this->order_details->with([
                'product.reviews',
                'product.flash_deal_product.flash_deal',
                'product.seller.shop',
                'product.wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'product.compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(10)
            ->get();

        $bestSellProduct?->map(function ($order) use ($current_date) {
            if(!isset($order->product)){
                return $order;
            }
            $flash_deal_status = 0;
            $flash_deal_end_date = 0;
            if (isset($order->product->flash_deal_product) && count($order->product->flash_deal_product) > 0) {
                $flash_deal = $order->product->flash_deal_product[0]->flash_deal;
                if ($flash_deal) {
                    $start_date = date('Y-m-d H:i:s', strtotime($flash_deal->start_date));
                    $end_date = date('Y-m-d H:i:s', strtotime($flash_deal->end_date));
                    $flash_deal_status = $flash_deal->status == 1 && (($current_date >= $start_date) && ($current_date <= $end_date)) ? 1 : 0;
                    $flash_deal_end_date = $flash_deal->end_date;
                }
            }
            $order->product['flash_deal_status'] = $flash_deal_status;
            $order->product['flash_deal_end_date'] = $flash_deal_end_date;
            return $order;
        });

        // Just for you portion
        if (auth('customer')->check()) {
            $orders = $this->order->where(['customer_id' => auth('customer')->id()])->with(['details'])->get();

            if ($orders) {
                $orders = $orders?->map(function ($order) {
                    $order_details = $order->details->map(function ($detail) {
                        $product = json_decode($detail->product_details);
                        $category = json_decode($product->category_ids)[0]->id;
                        $detail['category_id'] = $category;
                        return $detail;
                    });
                    $order['id'] = $order_details[0]->id;
                    $order['category_id'] = $order_details[0]->category_id;

                    return $order;
                });

                $categories = [];
                foreach ($orders as $order) {
                    $categories[] = ($order['category_id']);;
                }
                $ids = array_unique($categories);


                $just_for_you = $this->product->with([
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()
                ->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhere('category_ids', 'like', "%{$id}%");
                    }
                })
                ->inRandomOrder()
                ->take(8)
                ->get();
            } else {
                $just_for_you = $this->product->with([
                    'wish_list'=>function($query){
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list'=>function($query){
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    }
                ])->active()->inRandomOrder()->take(8)->get();
            }
        } else {
            $just_for_you = $this->product->with([
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])->active()->inRandomOrder()->take(8)->get();
        }
        // end just for you

        $topRated = $this->review->with([
                'product.seller.shop',
                'product.wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'product.compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(10)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }
        $mostViewed = Product::active()
            ->with([
                'seller.shop',
                'flash_deal_product.flash_deal',
                'wish_list'=>function($query){
                    return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                },
                'compare_list'=>function($query){
                    return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                }
            ])
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();
            
        $mostViewed?->map(function ($product) use ($current_date) {
        $flash_deal_status = 0;
        $flash_deal_end_date = 0;
        if (count($product->flash_deal_product) > 0) {
            $flash_deal = $product->flash_deal_product[0]->flash_deal;
            if ($flash_deal) {
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


        $deal_of_the_day = $this->deal_of_the_day->join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();
        $random_product = $this->product->active()->inRandomOrder()->first();

        $banner_list = ['Main Banner', 'Footer Banner', 'Sidebar Banner', 'Main Section Banner', 'Top Side Banner'];
        $banners = $this->banner->whereIn('banner_type', $banner_list)->where('published', 1)->orderBy('id', 'desc')->latest('created_at')->get();

        $main_banner = [];
        $footer_banner = [];
        $sidebar_banner = [];
        $main_section_banner = [];
        $top_side_banner = [];
        foreach($banners as $banner){
            if($banner->banner_type == 'Main Banner'){
                $main_banner[] = $banner;
            }elseif($banner->banner_type == 'Footer Banner'){
                $footer_banner[] = $banner->toArray();
            }elseif($banner->banner_type == 'Sidebar Banner'){
                $sidebar_banner[] = $banner;
            }elseif($banner->banner_type == 'Main Section Banner'){
                $main_section_banner[] = $banner;
            }elseif($banner->banner_type == 'Top Side Banner'){
                $top_side_banner[] = $banner;
            }
        }
        $sidebar_banner = $sidebar_banner ? $sidebar_banner[0] : [];
        $main_section_banner = $main_section_banner ? $main_section_banner[0] : [];
        $top_side_banner = $top_side_banner ? $top_side_banner[0] : [];
        $footer_banner = $footer_banner ? array_slice($footer_banner, 0, 2):[];

        $decimal_point = Helpers::get_business_settings('decimal_point_settings');
        $decimal_point_settings = !empty($decimal_point) ? $decimal_point : 0;
        $user = Helpers::get_customer();
        $categories = Category::with('childes.childes')->where(['position' => 0])->priority()->take(11)->get();

        //order again
        $order_again = $user != 'offline' ?
            $this->order->with('details.product')->where(['order_status' => 'delivered', 'customer_id' => $user->id])->latest()->take(8)->get()
            : [];

        $random_coupon = Coupon::with('seller')
            ->where(['status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->inRandomOrder()->take(3)->get();
        
        $computerCategory = $this->category->where('name', 'Computer')->first();
        $clothesCategory = $this->category->where('name', 'Clothes')->first();
        $cosmeticsCategory = $this->category->where('name', 'Cosmetics  Beauty')->first();
        $entertainmentCategory = $this->category->where('name', 'Entertainment')->first();
        $homeSuppliesCategory = $this->category->where('name', 'Home Supplies')->first();
        $telephonesCategory = $this->category->where('name', 'Telephones')->first();
        $phoneAccessoriesCategory = $this->category->where('name', 'Phone Accessories')->first();



        if ($computerCategory) {
            $computerProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $computerCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();

            $computerCategory['products'] = $computerProducts;
        }
        if ($clothesCategory) {
            $clothesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $clothesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $clothesCategory['products'] = $clothesProducts;
        }
        if ($cosmeticsCategory) {
            $cosmeticsProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $cosmeticsCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $cosmeticsCategory['products'] = $cosmeticsProducts;
        }
        if ($homeSuppliesCategory) {
            $homeSuppliesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $homeSuppliesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $homeSuppliesCategory['products'] = $homeSuppliesProducts;
        }
        if ($entertainmentCategory) {
            $entertainmentProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $entertainmentCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $entertainmentCategory['products'] = $entertainmentProducts;
        }
        if ($telephonesCategory) {
            $telephonesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $telephonesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $telephonesCategory['products'] = $telephonesProducts;
        }
           if ($phoneAccessoriesCategory) {
            $phoneAccessoriesProducts = Product::active()
                ->with([
                    'flash_deal_product',
                    'wish_list' => function ($query) {
                        return $query->where('customer_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                    'compare_list' => function ($query) {
                        return $query->where('user_id', Auth::guard('customer')->user()->id ?? 0);
                    },
                ])
                ->where('category_id', $phoneAccessoriesCategory->id)
                ->inRandomOrder()
                ->take(15)
                ->get();
        
            $phoneAccessoriesCategory['products'] = $phoneAccessoriesProducts;
        }
        
    
        
        
       
        return view(VIEW_FILE_NAMES['home'],
            compact(
                'topRated', 'bestSellProduct', 'latest_products', 'featured_products', 'deal_of_the_day', 'top_sellers',
                'home_categories', 'main_banner', 'footer_banner', 'random_product', 'decimal_point_settings', 'just_for_you', 'more_seller',
                'final_category', 'category_slider', 'order_again', 'sidebar_banner', 'main_section_banner', 'random_coupon', 'top_side_banner','stats',
                'featured_deals', 'flash_deals', 'categories' , 'special_sellers','mostViewed','computerProducts','clothesProducts','cosmeticsProducts','homeSuppliesProducts','entertainmentProducts','telephonesProducts','phoneAccessoriesProducts'
            )
        );
        
    }


}
