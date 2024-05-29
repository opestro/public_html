@extends('theme-views.layouts.app')

@section('title',translate('Shop Page'))


@push('css_or_js')
    @if($shop['id'] != 0)
        <meta property="og:image" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="og:title" content="{{ $shop->name}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="og:title" content="{{ $shop['name']}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @endif
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    @if($shop['id'] != 0)
        <meta property="twitter:card" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="twitter:card"
              content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @endif
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    
    <style> 
    .certified-badge img {
        width: 20px; 
        height: 20px; 
        margin-left: 5px; 
    }
    @media (max-width: 768px) {
    
    .title-mobile {
        font-size: 24px;
    }
}
    </style>
@endpush

@section('content')

    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3">
        <div class="container">
            <div class="rounded ov-hidden mb-3">
                @if($shop['id'] != 0)
                    <div class="store-banner dark-support bg-badge overflow-hidden" data-bg-img="" >
                        <img class="w-100" src="{{asset('storage/app/public/shop/banner')}}/{{$shop->banner}}"
                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'" alt="">
                    </div>
                @else
                    @php($banner=\App\CPU\Helpers::get_business_settings('shop_banner'))
                        <div class="store-banner dark-support bg-badge overflow-hidden" data-bg-img="" >
                            <img class="w-100" src="{{asset("storage/app/public/shop")}}/{{$banner??""}}"
                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'" alt="">
                        </div>
                @endif

                <div class="bg-primary-light p-3">
                    <div class="d-flex gap-4 flex-wrap">

                        @if($shop['id'] != 0)
                            <div class="media gap-3">
                                <div class="avatar rounded store-avatar">
                                    <div class="position-relative">
                                        <img src="{{asset('storage/app/public/shop')}}/{{$shop->image}}"
                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'" class="dark-support rounded img-fit" alt="">

                                        @if($seller_temporary_close || $inhouse_temporary_close)
                                        <span class="temporary-closed position-absolute">
                                            <span>{{translate('closed_now')}}</span>
                                        </span>
                                        @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                            $seller_id!=0 && $seller_vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date)
                                            <span class="temporary-closed position-absolute">
                                                <span>{{translate('closed_now')}}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="media-body d-flex flex-column gap-2">
                                    <h4 class="title-mobile">{{ $shop->name}} 
                                    @if ($followers > 1000 or $shop['id'] ==2723 or $shop['id'] ==2727)
                                        <span class="certified-badge">
                                        <img src="{{theme_asset('assets/img/badge.png')}}" alt="Badge">
                                    </span>
                                    @endif
                                    </h4>
                                    
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="star-rating text-gold fs-12">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $avg_rating)
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating >= ((int)$avg_rating+.30))
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="text-muted fw-semibold">({{round($avg_rating,1)}})</span>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        @else
                            <div class="media gap-3">
                                <div class="avatar rounded store-avatar">
                                    <div class="position-relative">
                                        <img src="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"
                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'" class="dark-support rounded img-fit" alt="">

                                        @if($seller_temporary_close || $inhouse_temporary_close)
                                        <span class="temporary-closed position-absolute">
                                            <span>{{translate('closed_now')}}</span>
                                        </span>
                                        @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                            $seller_id!=0 && $seller_vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date)
                                            <span class="temporary-closed position-absolute">
                                                <span>{{translate('closed_now')}}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="media-body d-flex flex-column gap-2">
                                    <h4>{{ $web_config['name']->value }}</h4>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="star-rating text-gold fs-12">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $avg_rating)
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating >= ((int)$avg_rating+.30))
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="text-muted fw-semibold">({{round($avg_rating,1)}})</span>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        @endif

                        <div class="d-flex gap-3 flex-wrap flex-grow-1">
                            
                             <!--
                            <div class="card flex-grow-1">
                                <div class="card-body grid-center">
                                    <div class="text-center">
                                        <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{round(($avg_rating*100)/5)}}%</h2>
                                        <p class="text-muted">{{translate("Positive_Review")}}</p>
                                    </div>
                                </div>
                            </div>
                            -->
                            <div class="card flex-grow-1">
                                <div class="card-body grid-center">
                                    <div class="text-center">
                                        <h2 class="follower_count" style="color: #FF4D00;">{{$followers}}</h2>
                                        <p class="text-muted">{{translate('Followers')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card flex-grow-1">
                                <div class="card-body grid-center">
                                    <div class="text-center">
                                        <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{$products_for_review}}</h2>
                                        <p class="text-muted">{{translate('Products')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap flex-lg-column flex-lg-down-grow-1 justify-content-center gap-3">
                            @if($seller_id!=0)
                                @if (auth('customer')->check())
                                    <button class="btn btn-primary flex-lg-down-grow-1 fs-16" data-bs-toggle="modal" data-bs-target="#contact_sellerModal">
                                        <i class="bi bi-chat-square-dots"></i>
                                    </button>
                                    @include('theme-views.layouts.partials.modal._chat-with-seller',['seller_id'=>$seller_id,'shop_id'=>$shop['id']])
                                @else
                                    <button class="btn btn-primary flex-lg-down-grow-1 fs-16" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="bi bi-chat-square-dots"></i>
                                    </button>
                                @endif
                            @endif

                            @if (auth('customer')->id() == '')
                                <button type="button" class="btn btn-outline-primary flex-lg-down-grow-1 fs-16" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-plus-lg"></i> {{ translate('follow') }}</button>
                            @else
                                <button type="button" class="btn btn-outline-primary flex-lg-down-grow-1 fs-16 follow_button" data-status="{{$follow_status}}"
                                data-titletext="{{translate('Are_you_sure')}}?" data-titletext2="{{translate('Want_to_unfollow_this_shop')}}!" onclick="shopFollowAction('{{$shop['id']}}')"> {{($follow_status == 0?translate('Follow'):translate('Unfollow'))}}</button>
                            @endif
                            
                            @if($shop['id'] != 0)
                            <a  href="tel:{{$shop->contact}}" class="btn btn-outline-primary flex-lg-down-grow-1 fs-16">{{translate('call_seller')}}</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <!--
            @if($shop['id'] != 0 && $shop->bottom_banner)
                <div class="">
                    <img src="{{asset('storage/app/public/shop/banner')}}/{{$shop->bottom_banner}}" class="dark-support rounded img-fit"
                    onerror="this.src='{{theme_asset('assets/img/image-place-holder-4:1.png')}}'"
                    alt="">
                </div>
            @elseif($shop['id'] == 0)
                @php($bottom_banner=\App\CPU\Helpers::get_business_settings('bottom_banner'))
                @if($bottom_banner)
                <div class="">
                    <img src="{{asset('storage/app/public/shop')}}/{{$bottom_banner ?? ""}}" class="dark-support rounded img-fit"
                         onerror="this.src='{{theme_asset('assets/img/image-place-holder-4:1.png')}}'"
                         alt="">
                </div>
                @endif
            @endif
            -->
        </div>

        <!-- Featured Products -->
        @if (count($featured_products) > 0)
        <section class="bg-primary-light">
            <div class="container">
                <div class="">
                    <div class="py-4">
                        <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 mb-sm-4">
                            <h2>{{translate('Featured_Products')}}</h2>
                            <div class="swiper-nav d-flex gap-2 align-items-center">
                                <div class="swiper-button-prev top-rated-nav-prev position-static rounded-10"></div>
                                <div class="swiper-button-next top-rated-nav-next position-static rounded-10"></div>
                            </div>
                        </div>
                        @if (count($featured_products) > 0)
                        <div class="swiper-container">
                            <!-- Swiper -->
                            <div class="position-relative">
                                <div class="swiper" data-swiper-loop="false" data-swiper-margin="20" data-swiper-autoplay="true" data-swiper-pagination-el="null" data-swiper-navigation-next=".top-rated-nav-next" data-swiper-navigation-prev=".top-rated-nav-prev" data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "320": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                                    <div class="swiper-wrapper">
                                        @foreach ($featured_products as $product)
                                        <div class="swiper-slide mx-w300">
                                            <!-- Single Product -->
                                            @include('theme-views.partials._product-large-card', ['product'=>$product])
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div>
                            {{translate('No_Featured_Product_Found')}}.
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Product -->
        <section>
            <div class="container">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                            <div class="">
                                <div class="d-flex gap-3 align-items-center">
                                    <h3 class="mb-1">{{translate('Search_Product')}}</h3>
                                    <a href="javascript:" class="text-primary text-decoration-underline fw-semibold">{{$products->count()}} {{translate('Item')}}</a>
                                </div>
                            </div>
                            <div class="">
                                <div class="d-flex flex-wrap flex-lg-nowrap gap-2">
                                    <div class="search-box">
                                        <form method="get" action="{{route('shopView',['id'=>$seller_id])}}">
                                          <div class="d-flex">
                                            <div class="select-wrap border d-flex align-items-center">
                                              <input type="search" class="form-control border-0 mx-w300 h-auto" name="product_name" value="{{ request('product_name') }}"
                                              placeholder="{{translate('Search_for_items')}} ...">
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                              <i class="bi bi-search"></i>
                                            </button>
                                          </div>
                                        </form>
                                    </div>

                                    <div class="border rounded custom-ps-3 py-2 d-flex align-items-center">
                                        <div class="d-flex gap-2">
                                            <div class="flex-middle gap-2">
                                                <i class="bi bi-sort-up-alt"></i>
                                                <span class="d-none d-sm-inline-block">{{translate('Sort_by')}} :</span>
                                            </div>
                                            <div class="dropdown product_view_sort_by">
                                                <button type="button" class="border-0 bg-transparent dropdown-toggle text-dark p-0 custom-pe-3" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{translate('default')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" id="sort_by_list">
                                                    <li class="sort_by-latest selected" data-value="latest">
                                                        <a class="d-flex" href="javascript:" onclick="filter('latest','{{translate('default')}}')">
                                                            {{translate('default')}}
                                                        </a>
                                                    </li>
                                                    <li class="sort_by-high-low" data-value="high-low">
                                                        <a class="d-flex" href="javascript:" onclick="filter('high-low','{{translate('High_to_Low_Price')}}')">
                                                            {{translate('High_to_Low_Price')}}
                                                        </a>
                                                    </li>
                                                    <li class="sort_by-low-high" data-value="low-high">
                                                        <a class="d-flex" href="javascript:" onclick="filter('low-high','{{translate('Low_to_High_Price')}}')">
                                                            {{translate('Low_to_High_Price')}}
                                                        </a>
                                                    </li>
                                                    <li class="sort_by-a-z" data-value="a-z">
                                                        <a class="d-flex" href="javascript:" onclick="filter('a-z','{{translate('A_to_Z_Order')}}')">
                                                            {{translate('A_to_Z_Order')}}
                                                        </a>
                                                    </li>
                                                    <li class="sort_by-z-a" data-value="z-a">
                                                        <a class="d-flex" href="javascript:" onclick="filter('z-a','{{translate('Z_to_A_Order')}}')">
                                                            {{translate('Z_to_A_Order')}}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border rounded custom-ps-3 py-2 d-flex align-items-center gap-2">
                                        <div class="d-flex gap-2">
                                            <div class="flex-middle gap-2">
                                                <i class="bi bi-sort-up-alt"></i>
                                                <span class="d-none d-sm-inline-block">{{translate('Show_Product')}} : </span>
                                            </div>

                                            <div class="dropdown">
                                                <button type="button" class="border-0 bg-transparent dropdown-toggle p-0 custom-pe-3" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{$data['data_from']=="best-selling"||$data['data_from']=="top-rated"||$data['data_from']=="featured_deal"||$data['data_from']=="latest"||$data['data_from']=="most-favorite"?
                                                    str_replace(['-', '_', '/'], ' ', translate($data['data_from'])):translate('Choose Option')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li class="{{$data['data_from']=='latest'? 'selected':''}}">
                                                        <a class="d-flex" href="{{route('shopView',['id'=> $data['id'],'data_from'=>'latest','page'=>1])}}">
                                                            {{translate('Latest_Products')}}
                                                        </a>
                                                    </li>
                                                    <li class="{{$data['data_from']=='best-selling'? 'selected':''}}">
                                                        <a class="d-flex" href="{{route('shopView',['id'=> $data['id'],'data_from'=>'best-selling','page'=>1])}}">
                                                            {{translate('Best_Selling')}}
                                                        </a>
                                                    </li>
                                                    <li class="{{$data['data_from']=='top-rated'? 'selected':''}}">
                                                        <a class="d-flex" href="{{route('shopView',['id'=> $data['id'],'data_from'=>'top-rated','page'=>1])}}">
                                                            {{translate('Top_Rated')}}
                                                        </a>
                                                    </li>
                                                    <li class="{{$data['data_from']=='most-favorite'? 'selected':''}}">
                                                        <a class="d-flex" href="{{route('shopView',['id'=> $data['id'],'data_from'=>'most-favorite','page'=>1])}}">
                                                            {{translate('Most_Favorite')}}
                                                        </a>
                                                    </li>
                                                    @if($web_config['featured_deals'])
                                                    <li class="{{$data['data_from']=='featured_deal'? 'selected':''}}">
                                                        <a class="d-flex" href="{{route('shopView',['id'=> $data['id'],'data_from'=>'featured_deal','page'=>1])}}">
                                                            {{translate('Featured_Deal')}}
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flexible-grid lg-down-1 gap-3" style="--width: 16rem">
                    <div class="card filter-toggle-aside">
                        <div class="d-flex d-lg-none pb-0 p-3 justify-content-end">
                            <button class="filter-aside-close border-0 bg-transparent">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <div class="card-body d-flex flex-column gap-4">
                            <!-- Categories -->
                            <div>
                                <h6 class="mb-3">{{translate('Categories')}}</h6>
                                <div class="products_aside_categories">
                                    <ul class="common-nav flex-column nav custom-scrollbar flex-nowrap custom_common_nav">
                                        @foreach($categories as $category)
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <a href="{{route('shopView',['id'=> $seller_id,'category_id'=>$category['id']])}}">{{$category['name']}}</a>
                                                @if ($category->childes->count() > 0)
                                                <span>
                                                    <i class="bi bi-chevron-right"></i>
                                                </span>
                                                @endif
                                            </div>
                                            <!-- Sub Menu -->
                                            @if ($category->childes->count() > 0)
                                            <ul class="sub_menu">
                                                @foreach($category->childes as $child)
                                                <li>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{route('shopView',['id'=> $seller_id,'category_id'=>$child['id']])}}">{{$child['name']}}</a>
                                                        @if ($child->childes->count() > 0)
                                                        <span>
                                                            <i class="bi bi-chevron-right"></i>
                                                        </span>
                                                        @endif
                                                    </div>

                                                    @if ($child->childes->count() > 0)
                                                    <ul class="sub_menu">
                                                        @foreach($child->childes as $ch)
                                                        <li>
                                                            <label class="custom-checkbox">
                                                                <a href="{{route('shopView',['id'=> $seller_id,'category_id'=>$ch['id']])}}">{{$ch['name']}}</a>
                                                            </label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @if (count($categories) > 10)
                                <div class="d-flex justify-content-center">
                                    <button class="btn-link text-primary btn_products_aside_categories">{{translate('More_Categories')}}...</button>
                                </div>
                                @endif
                            </div>

                            @if($web_config['brand_setting'])
                            <!-- Brands -->
                            <div>
                                <h6 class="mb-3">{{translate('Brands')}}</h6>
                                <div class="products_aside_brands">
                                    <ul class="common-nav nav flex-column pe-2">
                                        @foreach($brands as $brand)
                                        <li>
                                            <div class="flex-between-gap-3 align-items-center">
                                                <label class="custom-checkbox">
                                                    <a href="{{route('shopView',['id'=>$seller_id,'brand_id'=>$brand->id])}}">{{ $brand['name'] }}</a>
                                                </label>
                                                <span class="badge bg-badge rounded-pill text-dark">
                                                    {{$brand->count}}
                                                </span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if (count($brands) > 10)
                                <div class="d-flex justify-content-center">
                                    <button class="btn-link text-primary btn_products_aside_brands">{{translate('More_Brands')}}...</button>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Ratings -->
                            <div id="ajax-review_partials">
                                @include('theme-views.partials._products_review_partials', ['ratings'=>$ratings])
                            </div>

                            <!-- Price -->
                            <div>
                                <h6 class="mb-3">{{translate('Price')}}</h6>
                                <div class="d-flex align-items-end gap-2">
                                    <div class="form-group">
                                        <label for="min_price" class="mb-1">{{translate('Min')}}</label>
                                        <input type="number" id="min_price" class="form-control form-control--sm" placeholder="$0">
                                    </div>
                                    <div class="mb-2">-</div>
                                    <div class="form-group">
                                        <label for="max_price" class="mb-1">{{translate('Max')}}</label>
                                        <input type="number" id="max_price" class="form-control form-control--sm" placeholder="$1,000">
                                    </div>
                                    <button class="btn btn-primary py-1 px-2 fs-13" onclick="sortByfilterBy()"><i class="bi bi-chevron-right"></i></button>
                                </div>

                                <section class="range-slider">
                                    <span class="full-range"></span>
                                    <span class="incl-range"></span>
                                    <input name="rangeOne" value="0" min="0" max="10000" step="1" type="range" id="price_rangeMin">
                                    <input name="rangeTwo" value="5000" min="0" max="10000" step="1" type="range" id="price_rangeMax">
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="d-flex flex-wrap flex-lg-nowrap align-items-start justify-content-between gap-3 mb-2">
                            <!-- <div class="flex-middle gap-3"></div> -->
                            <div class="d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between gap-2 gap-md-3 flex-grow-1">
                                <button class="toggle-filter square-btn btn btn-outline-primary rounded d-lg-none">
                                    <i class="bi bi-funnel"></i>
                                </button>

                                <ul class="product-view-option option-select-btn gap-3">
                                    <li>
                                        <label>
                                            <input type="radio" name="product_view" value="grid-view" hidden=""
                                            {{!session()->has('product_view_style')?'checked':''}}
                                            {{(session()->get('product_view_style') == 'grid-view'?'checked':'')}} id="grid-view">
                                            <span class="py-2 d-flex align-items-center gap-2"><i class="bi bi-grid-fill"></i> {{translate('Grid_View')}}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="product_view" value="list-view" hidden=""
                                            {{(session()->get('product_view_style') == 'list-view'?'checked':'')}} id="list-view">
                                            <span class="py-2 d-flex align-items-center gap-2"><i class="bi bi-list-ul"></i> {{translate('List_View')}}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))

                        <div id="ajax-products-view">
                            @include('theme-views.product._ajax-products',['products'=>$products,'decimal_point_settings'=>$decimal_point_settings])
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- End Main Content -->

    <span id="filter_url" data-url="{{url('/')}}/shopView/{{$shop['id']}}"></span>
    <span id="product_view_style_url" data-url="{{route('product_view_style')}}"></span>
    <span id="shop_follow_url" data-url="{{route('shop_follow')}}"></span>
    <input type="hidden" value="{{$data['data_from']}}" id="data_from">
    <input type="hidden" value="{{$data['id']}}" id="data_id">
    <input type="hidden" value="{{$data['name']}}" id="data_name">
    <input type="hidden" value="{{$data['min_price']}}" id="data_min_price">
    <input type="hidden" value="{{$data['max_price']}}" id="data_max_price">

    <!-- End Main Content -->
@endsection
