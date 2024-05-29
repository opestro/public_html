<style>
    
.search-space {
   background-color: var(--bs-body-bg);
   margin-right: 20%;
   margin-left: 20%;
   margin-top: 10px;
   margin-bottom: 10px;
   border-radius: 20px;
  
}
.search-space-input{
    border-radius: 20px;
    border: 2px solid #FF4D00;
    
}
.search-space-input .search-card {
    margin-top: 20px;
}

button#mobile-search-button[type="submit"] {
   
    border-radius: 50%;
    background-color: #FF4D00;
    color: white;
    padding: 10px;
    margin-right: 2px;
    margin-top: 2px;
    margin-bottom: 2px;
    border: none;
}

@media (max-width: 576px) {
 .search-space {

   margin-right: 30px;
   margin-left: 30px;

  
}
}


</style>
<!-- Top Offer Bar -->
@if (isset($web_config['announcement']) && $web_config['announcement']['status']==1)
<div class="offer-bar py-3" data-bg-img="{{theme_asset('assets/img/media/top-offer-bg.png')}}">
    <div class="d-flex gap-2 align-items-center">
        <div class="offer-bar-close">
            <i class="bi bi-x-lg"></i>
        </div>
        <div class="top-offer-text flex-grow-1 d-flex justify-content-center fw-semibold">
            {{ $web_config['announcement']['announcement'] }}
        </div>
    </div>
</div>
@endif
@php($categories = \App\Model\Category::with('childes.childes')->where(['position'=> 0])->priority()->take(11)->get())
@php($brands = \App\Model\Brand::active()->take(15)->get())
<!-- Header -->
<header class="header">
    <div class="header-top py-2">
        <div class="container">
            <div class="d-flex align-items-center flex-wrap justify-content-between gap-2">
                <a href="tel:{{ $web_config['phone']->value }}" class="d-flex gap-2 align-items-center">
                    <i class="bi bi-telephone text-primary"></i>
                    {{ $web_config['phone']->value }}
                </a>

                <ul class="nav justify-content-center justify-content-sm-end align-items-center gap-4">
                    <li>
                        <div class="language-dropdown">
                            @if($web_config['currency_model']=='multi_currency')
                                <button
                                    type="button"
                                    class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    {{session('currency_code')}} {{session('currency_symbol')}}
                                </button>
                                <ul class="dropdown-menu" style="--bs-dropdown-min-width: 10rem">
                                    @foreach ($web_config['currencies'] as $key => $currency)
                                    <li onclick="currency_change('{{$currency['code']}}')"><a href="javascript:">{{ $currency->name }}</a></li>
                                    @endforeach
                                    <span id="currency-route" data-currency-route="{{route('currency.change')}}"></span>
                                </ul>
                            @endif
                        </div>
                    </li>
                    <li>
                        <div class="language-dropdown">
                            <button
                                type="button"
                                class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                @php( $local = \App\CPU\Helpers::default_lang())
                                @foreach(json_decode($language['value'],true) as $data)
                                    @if($data['code']==$local)
                                        <img width="20" src="{{theme_asset('assets/img/flags')}}/{{ $data['code'].'.png' }}" class="dark-support" alt="Eng"/>
                                        {{ ucwords($data['name']) }}
                                    @endif
                                @endforeach
                            </button>
                            <ul class="dropdown-menu" style="--bs-dropdown-min-width: 10rem">
                                @foreach(json_decode($language['value'],true) as $key =>$data)
                                    @if($data['status']==1)
                                        <li>
                                            <a class="d-flex gap-2 align-items-center" href="{{route('lang',[$data['code']])}}">
                                                <img width="20" src="{{theme_asset('assets/img/flags')}}/{{ $data['code'].'.png' }}" loading="lazy" class="dark-support" alt="{{$data['name']}}"/>
                                                {{ ucwords($data['name']) }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <!--
                    @if($web_config['seller_registration'])
                        <li class="d-flex">
                            <div class="language-dropdown">
                                <button type="button" class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ \App\CPU\translate('Seller Zone') }}
                                </button>
                                <ul class="dropdown-menu" style="--bs-dropdown-min-width: 10rem">
                                    <li>
                                        <a class="d-flex" href="{{ route('steps-seller') }}">{{ \App\CPU\translate('How to become a seller') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('shop.apply') }}" class="d-flex">
                                            <div class="fz-16">{{ translate('Become_a') }} {{ translate('Seller') }}</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('seller.auth.login') }}" class="d-flex">
                                            <div class="fz-16">{{ translate('Seller') }} {{ translate('login') }}</div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                -->
                </ul>
            </div>
        </div>
    </div>
    <div class="header-middle border-bottom py-2 d-none d-xl-block">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between gap-3" style="direction: ltr;">
                <a class="logo" href="{{route('home')}}">
                                <img id="logoToSwitchMobile" src="https://nichen.net/storage/app/company/Logo.png" class="dark-support svg" style="width: 200px; height: auto;" alt=""/>
                </a>
                <div class="search-box position-relative">
                    <form action="{{route('products')}}" type="submit">
                        <div class="d-flex">
                            <div
                                class="select-wrap focus-border border border-end-logical-0 d-flex align-items-center"
                            >
                                <div class="border-end">
                                    <div class="dropdown search_dropdown">
                                        <button type="button" class="border-0 px-3 bg-transparent dropdown-toggle text-dark py-0" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ translate('All_Categories') }}
                                        </button>
                                        <input type="hidden" name="search_category_value" id="search_category_value" value="all">
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="d-flex" data-value="all" href="javascript:">
                                                    {{ translate('All_Categories') }}
                                                </a>
                                            </li>
                                            @if($categories)
                                                @foreach($categories as $category)
                                                    <li>
                                                        <a class="d-flex" data-value="{{ $category->id }}" href="javascript:">
                                                            {{ $category['name'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <input
                                    type="search"
                                    class="form-control border-0 focus-input search-bar-input" name="name" onkeyup="global_search()"
                                    placeholder="{{ translate('Search_for_items_or_store') }}..."
                                />
                            </div>
                            <input name="data_from" value="search" hidden>
                            <input name="page" value="1" hidden>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <div class="card search-card __inline-13 position-absolute z-99 w-100 bg-white top-100 start-0 search-result-box" style="display:none;"></div>


                </div>
                <div class="offer-btn">
                    @if($web_config['header_banner'])
                        <a href="{{ $web_config['header_banner']['url'] }}">
                            <img
                                width="180"
                                src="{{asset('storage/app/public/banner')}}/{{$web_config['header_banner']['photo']}}"
                                onerror="this.src='{{theme_asset('assets/img/header-banner-placeholder.png')}}'"
                                loading="lazy"
                                class="dark-support"
                                alt=""
                            />
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="header-main love-sticky py-1 py-lg-3 py-xl-0 shadow-sm">
        <div class="container">
            <!-- Aside -->
            <aside class="aside d-flex flex-column d-xl-none">
                <div class="aside-close p-3 pb-2">
                    <i class="bi bi-x-lg"></i>
                </div>
                <!-- Aside Body -->
                <div>
                    <div class="aside-body" data-trigger="scrollbar">
                        <!-- Search -->
                        

                        <!-- Nav -->
                        <ul class="main-nav nav">
                            <li>
                                <a href="{{route('categories')}}#">{{ translate('categories') }}</a>
                                <!-- Sub Menu -->
                                <ul class="sub_menu">
                                    @foreach($categories as $key=>$category)
                                    <li>
                                        <a href="javascript:" onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                            {{$category['name']}}
                                        </a>
                                        @if ($category->childes->count() > 0)
                                            <ul class="sub_menu">
                                                @foreach($category['childes'] as $subCategory)
                                                <li>
                                                    <a href="javascript:" onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                                        {{$subCategory['name']}}
                                                    </a>
                                                    @if($subCategory->childes->count()>0)
                                                    <ul class="sub_menu">
                                                        @foreach($subCategory['childes'] as $subSubCategory)
                                                            <li>
                                                                <a href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">
                                                                    {{$subSubCategory['name']}}
                                                                </a>
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
                            </li>
                            <li>
                                <a href="{{route('home')}}">{{ translate('Home') }}</a>
                            </li>
                            @if($web_config['featured_deals']->count()>0 || $web_config['flash_deals'])
                            <li>
                                <a href="javascript:">{{ translate('Offers')}}</a>
                                <ul class="sub_menu">
                                    @if($web_config['featured_deals']->count()>0)
                                    <li><a href="{{route('products',['data_from'=>'featured_deal'])}}">{{ translate('Featured_Deal') }}</a></li>
                                    @endif

                                    @if($web_config['flash_deals'])
                                    <li><a href="{{route('flash-deals',[$web_config['flash_deals']?$web_config['flash_deals']['id']:0])}}">{{ translate('Flash_Deal') }}</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                           

                            @if($web_config['brand_setting'])
                            <li>
                                <a href="javascript:">{{ translate('brands') }}</a>
                                <!-- Sub Menu -->
                                <ul class="sub_menu">
                                    @foreach($brands as $brand)
                                        <li>
                                            <a href="{{ route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1]) }}">{{ $brand->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif

                            <li>
                                <a class="d-flex gap-2 align-items-center" href="{{route('products',['data_from'=>'discounted','page'=>1])}}">
                                    {{ translate('Discounted_Products')}}
                                    <i class="bi bi-patch-check-fill text-warning"></i>
                                </a>
                            </li>
                            
                            @if($web_config['seller_registration'])
                            <li class="d-xl-none">
                                <a href="{{ route('offers') }}" class="d-flex">
                                    <div class="fz-16">{{ \App\CPU\translate('Our_offers') }}</div>
                                </a>
                            </li>
                            @endif
                            
                            
                             @if($web_config['seller_registration'])
                            <li class="d-xl-none">
                                <a href="{{ route('steps-seller') }}" class="d-flex">
                                    <div class="fz-16">{{ \App\CPU\translate('How to become a seller') }}</div>
                                </a>
                            </li>
                            @endif
                            
                            @if($web_config['seller_registration'])
                            <li class="d-xl-none" >
                                <a href="{{route('shop.apply')}}" class="d-flex">
                                    <div class="fz-16">{{ translate('Become_a')}} {{ translate('Seller')}}</div>
                                </a>
                            </li>
                            @endif

                            @if(Session::get('device') !== 'mobile' && $web_config['seller_registration'])
                                <li class="d-xl-none" id="hide-button" >
                                    <a href="{{ route('seller.auth.login') }}" class="d-flex">
                                        <div class="fz-16">{{ translate('Seller') }} {{ translate('login') }}</div>
                                    </a>
                                </li>
                            @endif
                            

        
                        </ul>
                        <!-- End Nav -->
                    </div>

                    <div class="d-flex align-items-center gap-2 justify-content-between p-4">
                        <span class="text-dark">{{ translate('theme_mode') }}</span>
                        <div class="theme-bar p-1">
                            <button onclick="switchToLightMode()" class="light_button active">
                                <img src="{{theme_asset('assets/img/svg/light.svg')}}" alt="" class="svg">
                            </button>
                            <button onclick="switchToDarkMode()" class="dark_button">
                                <img src="{{theme_asset('assets/img/svg/dark.svg')}}" alt="" class="svg">
                            </button>
                        </div>
                    </div>
                </div>

                @if(auth('customer')->check())
                <div class="d-flex justify-content-center mb-5 pb-5 mt-auto px-4">
                    <a href="{{route('customer.auth.logout')}}" class="btn btn-primary w-100">{{ translate('logout') }}</a>
                </div>
                @else
                    <div class="d-flex justify-content-center mb-5 pb-5 mt-auto px-4">
                        <a  href="" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-primary w-100">
                            {{ translate('Customer_login') }} / {{ translate('register') }}
                        </a>
                    </div>
                @endif
            </aside>

            <div class="d-flex justify-content-between gap-3 align-items-center position-relative">
                <div class="d-flex align-items-center gap-3">
                    <!-- Header Category Dropdown -->
                    <div class="dropdown d-none d-xl-block">
                        <button
                            class="btn btn-primary rounded-0 text-uppercase fw-bold fs-14 dropdown-toggle select-category-button"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-list fs-4"></i>
                            {{ translate('Select_Category')}}
                        </button>
                        <ul class="dropdown-menu dropdown--menu">
                            @foreach($categories as $key=>$category)
                                @if($key<8)
                                    <li class="{{ $category->childes->count() > 0 ? 'menu-item-has-children':'' }}">
                                        <a href="javascript:" onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                            {{$category['name']}}
                                        </a>
                                        @if ($category->childes->count() > 0)
                                        <ul class="sub-menu">
                                            @foreach($category['childes'] as $subCategory)
                                            <li class="{{ $subCategory->childes->count()>0 ? 'menu-item-has-children':'' }}">
                                                <a href="javascript:" onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                                    {{$subCategory['name']}}
                                                </a>
                                                @if($subCategory->childes->count()>0)
                                                <ul class="sub-menu">
                                                    @foreach($subCategory['childes'] as $subSubCategory)
                                                        <li>
                                                            <a href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">
                                                                {{$subSubCategory['name']}}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                            <li>
                                <a href="{{route('categories')}}" class="btn-link text-primary">
                                    {{ translate('view_all') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Header Category Dropdown -->

                    <!-- Main Nav -->
                    <div class="nav-wrapper">
                        <div class="d-xl-none">
                          <a class="logo" href="{{route('home')}}">
                                <img id="logoToSwitch" src="https://nichen.net/storage/app/company/Logo.png" class="dark-support svg h-45" alt=""/>
                            </a>
                        </div>
                        <ul class="nav main-menu align-items-center d-none d-xl-flex flex-nowrap">
                            <li class="{{request()->is('/')?'active':''}}">
                                <a href="{{route('home')}}">{{ translate('Home')}}</a>
                            </li>
                            @if($web_config['featured_deals']->count()>0 || $web_config['flash_deals'])
                            <li>
                                <a class="cursor-pointer">{{ translate('Offers')}}</a>
                                <ul class="sub-menu">
                                    @if($web_config['featured_deals']->count()>0)
                                        <li>
                                            <a href="{{route('products',['data_from'=>'featured_deal'])}}">{{ translate('Featured_Deal') }}</a>
                                        </li>
                                    @endif
                                    

                                    @if($web_config['flash_deals'])
                                    <li>
                                        <a href="{{route('flash-deals',[$web_config['flash_deals']?$web_config['flash_deals']['id']:0])}}">{{ translate('Flash_Deal') }}</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                           
                            @if($web_config['brand_setting'])
                            <li>
                                <a class="cursor-pointer">{{ translate('brands') }}</a>
                                <div class="sub-menu megamenu p-3"
                                    style="--bs-dropdown-min-width: max-content">
                                    <div class="d-flex gap-4">
                                        <div class="column-2">
                                            @foreach($brands as $brand)
                                                <a href="{{ route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1]) }}"
                                                   class="media gap-3 align-items-center border-bottom">
                                                    <div class="avatar rounded-circle"
                                                        style="--size: 1.25rem">
                                                        <img
                                                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                                            src="{{asset("storage/app/public/brand")}}/{{ $brand->image }}"
                                                            loading="lazy"
                                                            class="img-fit rounded-circle dark-support"
                                                            alt=""/>
                                                    </div>
                                                    <div class="media-body text-truncate"
                                                         style="--width: 7rem"
                                                         title="Bata">
                                                        {{ $brand->name }}
                                                    </div>
                                                </a>
                                            @endforeach
                                                <div class="d-flex">
                                                    <a href="{{route('brands')}}"
                                                       class="fw-bold text-primary d-flex justify-content-center">{{ translate('view_all') }}...
                                                    </a>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if ($web_config['discount_product']>0)
                                <li class="">
                                    <a class="d-flex gap-2 align-items-center discount-product-menu {{request()->is('/')?'active':''}}" href="{{route('products',['data_from'=>'discounted','page'=>1])}}">
                                        {{ translate('discounted_products') }}
                                        <i class="bi bi-patch-check-fill text-warning"></i></a>
                                </li>
                            @endif
                            
                            @if($web_config['seller_registration'])
                                <li  class="{{request()->is('seller-zone*')?'active':''}}">
                                        <a   href="#" class="cursor-pointer">
                                            <span style="color:#FF4D00;">{{ \App\CPU\translate('Seller_zone') }}</span>
                                        </a>
                                        <ul class="sub-menu">
                                            
                                             <li>
                                                <a href="{{ route('offers') }}">{{ \App\CPU\translate('Our_offers') }}</a>
                                            </li>
                                            
                                            <li>
                                                <a href="{{ route('steps-seller') }}">{{ \App\CPU\translate('How to become a seller') }}</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('shop.apply') }}">
                                                    <span>{{ translate('Become a') }} {{ translate('Seller') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('seller.auth.login') }}">
                                                    <span>{{ translate('Seller') }} {{ translate('login') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                </li>
                            @endif




                    
                        </ul>
                    </div>
                    <!-- End Main Nav -->
                </div>
                 
                <ul class="list-unstyled list-separator mb-0 pe-2">
                    @if(auth('customer')->check())
                    <div class="d-none d-md-block">
                        <span class="text-dark me-2">{{\App\CPU\translate('hello')}}, {{auth('customer')->user()->f_name}}</span>
                    </div>

                        <li class="login-register d-flex align-items-center gap-4">
                            <div class="profile-dropdown">
                                
                                <button
                                    type="button"
                                    class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0 user"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <span class="avatar overflow-hidden header-avatar rounded-circle" style="--size: 1.5rem">
                                      <img
                                          loading="lazy"
                                          src="{{ theme_asset('assets/img/icons/profile-icon.png') }}"
                                          onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                          class="img-fit"
                                          alt=""
                                      />
                                      
                                    </span>
                                </button>
                                <ul class="dropdown-menu" style="--bs-dropdown-min-width: 10rem">
                                    <li ><a href="{{route('account-oder')}}">{{ translate('my_order') }}</a></li>
                                    <li ><a href="{{route('user-profile')}}">{{ translate('my_profile') }}</a></li>
                                    <li ><a href="{{route('customer.auth.logout')}}">{{ translate('logout') }}</a></li>
                                </ul>
                            </div>
                            <div class="menu-btn d-xl-none">
                                <i class="bi bi-list fs-30"></i>
                            </div>
                        </li>
                    @else
                        <li class="login-register d-flex gap-4" id="sign-in-up">
                            <button
                                class="media gap-2 align-items-center text-uppercase fs-12 bg-transparent border-0 p-0"
                                data-bs-toggle="modal"
                                data-bs-target="#loginModal"
                            >
                                <span class="avatar header-avatar rounded-circle d-xl-none" style="--size: 1.5rem">
                                    <img
                                        loading="lazy"
                                        src="{{theme_asset('assets/img/no-auth-account-img.png')}}"
                                        class="img-fit rounded-circle"
                                        alt=""
                                    />
                              </span>
                                <span class="media-body d-none d-xl-block hover-primary">{{ translate('Customer_login') }} / {{ translate('register') }}</span>
                            </button>
                            <div class="menu-btn d-xl-none">
                                <i class="bi bi-list fs-30"></i>
                            </div>
                        </li>
                    @endif
                    <li class="d-none d-xl-block">
                        @if(auth('customer')->check())
                        <a href="{{ route('compare-list') }}" class="position-relative">
                            <i class="bi bi-repeat fs-18"></i>
                            <span class="count compare_list_count_status">{{session()->has('compare_list')?count(session('compare_list')):0}}</span>
                        </a>
                        @else
                            <a href="javascript:" class="position-relative" data-bs-toggle="modal"
                               data-bs-target="#loginModal">
                                <i class="bi bi-repeat fs-18"></i>
                            </a>
                        @endif
                    </li>
                    <li class="d-none d-xl-block">
                        @if(auth('customer')->check())
                        <a href="{{ route('wishlists') }}" class="position-relative">
                            <i class="bi bi-heart fs-18"></i>
                            <span class="count wishlist_count_status">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                        </a>
                        @else
                            <a href="javascript:" class="position-relative" data-bs-toggle="modal"
                               data-bs-target="#loginModal">
                                <i class="bi bi-heart fs-18"></i>
                            </a>
                        @endif

                    </li>
                    <li class="d-none d-xl-block" id="cart_items">
                        @include('theme-views.layouts.partials._cart')
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="header-main d-xl-none d-xxl-none search-space">
  
            <form action="{{route('products')}}" class="mb-3 search-space-input">
                <div class="search-bar">
                    <input type="search" name="name" class="form-control search-bar-input-mobile" autocomplete="off" placeholder="{{ translate('Search_for_items') }}...">
                    <input name="data_from" value="search" hidden="">
                    <input name="page" value="1" hidden="">
                    <button id="mobile-search-button" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="card search-card __inline-13 position-absolute z-99 w-100 bg-white start-0 search-result-box d--none"></div>
            </form>
        </div>
    </div>
    
</header>



<script>
    var defaultLightImage = "https://nichen.net/storage/app/company/Logo.png";
    var defaultDarkImage = "https://nichen.net/storage/app/company/LogoBG.png";
    var arabicLightImage = "https://nichen.net/storage/app/company/Logo-Dark-AR.png";
    var arabicDarkImage = "https://nichen.net/storage/app/company/Logo-Light-AR.png";
    
   
    

    // Function to set the logo based on the active mode and language direction
    function setLogoBasedOnMode() {
        var mode = localStorage.getItem('theme_mode'); // Get the stored theme mode
        var languageDirection = document.documentElement.dir; // Get the language direction

        var lightImage = (languageDirection === 'rtl') ? arabicLightImage : defaultLightImage;
        var darkImage = (languageDirection === 'rtl') ? arabicDarkImage : defaultDarkImage;
        
        

        // Check the active mode and set the logo accordingly
        if (mode === 'dark') {
            document.getElementById('logoToSwitch').src = darkImage;
            document.getElementById('logoToSwitchMobile').src = darkImage;
            
        } else {
            document.getElementById('logoToSwitch').src = lightImage;
            document.getElementById('logoToSwitchMobile').src = lightImage;
            
           
        }
    }

   
    var defaultLightLogo = new Image();
    defaultLightLogo.src = defaultLightImage;

    var defaultDarkLogo = new Image();
    defaultDarkLogo.src = defaultDarkImage;

    var arabicLightLogo = new Image();
    arabicLightLogo.src = arabicLightImage;

    var arabicDarkLogo = new Image();
    arabicDarkLogo.src = arabicDarkImage;

    
    setLogoBasedOnMode();

    function switchToLightMode() {
        var languageDirection = document.documentElement.dir; 
        var lightImage = (languageDirection === 'rtl') ? arabicLightImage : defaultLightImage;

        document.getElementById('logoToSwitch').src = lightImage;
        document.getElementById('logoToSwitchMobile').src = lightImage;
        

        $('.theme-support').attr('src', lightImage);
        localStorage.setItem('theme_mode', 'light'); 
    }

    function switchToDarkMode() {
        var languageDirection = document.documentElement.dir;
        var darkImage = (languageDirection === 'rtl') ? arabicDarkImage : defaultDarkImage;

        document.getElementById('logoToSwitch').src = darkImage;
        document.getElementById('logoToSwitchMobile').src = darkImage;
     
    
    
        $('.theme-support').attr('src', darkImage);
        localStorage.setItem('theme_mode', 'dark');
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            $(document).ready(function () {

                function setLogoBasedOnMode() {
                    var mode = localStorage.getItem('theme_mode');
        
                    
                    if (mode === 'dark') {
                        $('.theme-support').attr('src', 'https://nichen.net/storage/app/company/LogoBG.png');
                    } else {
                        $('.theme-support').attr('src', 'https://nichen.net/storage/app/company/Logo.png');
                    }
                }
        
               
                setLogoBasedOnMode();
        
                
                $(window).on('storage', function (e) {
                    if (e.originalEvent.key === 'theme_mode') {
                        setLogoBasedOnMode();
                    }
                });
            });
        </script>
        

