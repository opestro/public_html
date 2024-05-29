@extends('theme-views.layouts.app')

@section('title', translate($product['name']))


@push('css_or_js')
    <meta name="description" content="{{$product->slug}}">
    <meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @if($product->added_by=='seller')
        <meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
    @elseif($product->added_by=='admin')
        <meta name="author" content="{{$web_config['name']->value}}">
    @endif
    <!-- Viewport-->

    @if($product['meta_image'])
        <meta property="og:image" content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
    @else
        <meta property="og:image" content="{{asset("storage/app/public/product/thumbnail")}}/{{$product->thumbnail}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/app/public/product/thumbnail/")}}/{{$product->thumbnail}}"/>
    @endif

    @if($product['meta_title'])
        <meta property="og:title" content="{{$product->meta_title}}"/>
        <meta property="twitter:title" content="{{$product->meta_title}}"/>
    @else
        <meta property="og:title" content="{{$product->name}}"/>
        <meta property="twitter:title" content="{{$product->name}}"/>
    @endif
    <meta property="og:url" content="{{route('product',[$product->slug])}}">

    @if($product['meta_description'])
        <meta property="twitter:description" content="{!! $product['meta_description'] !!}">
        <meta property="og:description" content="{!! $product['meta_description'] !!}">
    @else
        <meta property="og:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="twitter:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @endif
    <meta property="twitter:url" content="{{route('product',[$product->slug])}}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/lightbox.min.css') }}">
    
@endpush

@section('content')

    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 pt-3 mb-sm-5">
        <div class="container">
            <div class="row gx-3 gy-4">
                <div class="col-lg-8 col-xl-9">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="quickview-content">
                                <div class="row gy-4">
                                    <div class="col-lg-5">
                                        <!-- Product Details Image Wrap -->
                                        <div class="pd-img-wrap position-relative h-100">
                                            <div class="swiper-container quickviewSlider2 border rounded"
                                                 style="--bs-border-color: #d6d6d6">
                                                <div class="product__actions d-flex flex-column gap-2">
                                                    <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
                                                       id="wishlist-{{$product['id']}}"
                                                       class="btn-wishlist add_to_wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist_status == 1?'wishlist_icon_active':'')}}"
                                                       title="{{translate('add_to_wishlist')}}">
                                                        <i class="bi bi-heart"></i>
                                                    </a>
                                                    <div class="product-share-icons">
                                                        <a href="javascript:" title="Share">
                                                            <i class="bi bi-share-fill"></i>
                                                        </a>

                                                        <ul>
                                                            <li>
                                                                <a href="javascript:"
                                                                   onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'facebook.com/sharer/sharer.php?u='); return false;">
                                                                    <i class="bi bi-facebook"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:"
                                                                   onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'twitter.com/intent/tweet?text='); return false;">
                                                                    <i class="bi bi-twitter"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:"
                                                                   onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'linkedin.com/shareArticle?mini=true&url='); return false;">
                                                                    <i class="bi bi-linkedin"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:"
                                                                   onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'api.whatsapp.com/send?text='); return false;">
                                                                    <i class="bi bi-whatsapp"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                @if($product->images!=null && json_decode($product->images)>0)
                                                   <div class="swiper-wrapper">
                                                        @if(json_decode($product->colors) && $product->color_image)
                                                            @foreach (json_decode($product->color_image) as $key => $photo)
                                                                @if($photo->color != null)
                                                                    <div class="swiper-slide position-relative">
                                                                        @if ($product->discount > 0 && $product->discount_type === "percent")
                                                                            <span class="product__discount-badge">-{{$product->discount}}%</span>
                                                                        @elseif($product->discount > 0)
                                                                            <span class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                                        @endif
                                                                        <img
                                                                            src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                                            class="dark-support rounded" alt=""
                                                                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                    </div>
                                                                @else
                                                                    <div class="swiper-slide position-relative">
                                                                        @if ($product->discount > 0 && $product->discount_type === "percent")
                                                                            <span class="product__discount-badge">-{{$product->discount}}%</span>
                                                                        @elseif($product->discount > 0)
                                                                            <span class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                                        @endif
                                                                        <img
                                                                            src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                                            class="dark-support rounded" alt=""
                                                                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @foreach (json_decode($product->images) as $key => $photo)
                                                                <div class="swiper-slide position-relative">
                                                                    @if ($product->discount > 0 && $product->discount_type === "percent")
                                                                        <span class="product__discount-badge">-{{$product->discount}}%</span>
                                                                    @elseif($product->discount > 0)
                                                                        <span class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                                    @endif
                                                                    <img
                                                                        src="{{asset("storage/app/public/product/$photo")}}"
                                                                        class="dark-support rounded" alt=""
                                                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mt-2">
                                                <div class="quickviewSliderThumb2 swiper-container position-relative ">
                                                    @if($product->images!=null && json_decode($product->images)>0)
                                                        <div
                                                            class="swiper-wrapper auto-item-width justify-content-center"
                                                            style="--width: 4rem; --bs-border-color: #d6d6d6">
                                                            @if(json_decode($product->colors) && $product->color_image)
                                                                @foreach (json_decode($product->color_image) as $key => $photo)
                                                                    @if($photo->color != null)
                                                                        <div class="swiper-slide"
                                                                             id="preview-img{{$key}}">
                                                                            <img
                                                                                src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                                                class="dark-support rounded border"
                                                                                alt="Product thumb"
                                                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                        </div>
                                                                    @else
                                                                        <div class="swiper-slide">
                                                                            <img
                                                                                src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                                                class="dark-support rounded border"
                                                                                alt="Product thumb"
                                                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                @foreach (json_decode($product->images) as $key => $photo)
                                                                    <div class="swiper-slide" id="preview-img{{$key}}">
                                                                        <img
                                                                            src="{{asset("storage/app/public/product/$photo")}}"
                                                                            class="dark-support rounded border"
                                                                            alt="Product thumb"
                                                                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @endif

                                                    <div class="swiper-button-next swiper-quickview-button-next"
                                                         style="--size: 1.5rem"></div>
                                                    <div class="swiper-button-prev swiper-quickview-button-prev"
                                                         style="--size: 1.5rem"></div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End Product Details Image Wrap -->
                                    </div>

                                    <div class="col-lg-7">
                                        <!-- Product Details Content -->
                                        <div class="product-details-content position-relative">

                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                                <h2 class="product_title">{{$product->name}}</h2>

                                                @if ($product->discount > 0 && $product->discount_type === "percent")
                                                    <span
                                                        class="product__save-amount">{{translate('save')}} {{$product->discount}}%</span>
                                                @elseif($product->discount > 0)
                                                    <span
                                                        class="product__save-amount">{{translate('save')}} {{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                @endif

                                            </div>

                                            <div class="d-flex gap-2 align-items-center mb-2">
                                                <div class="star-rating text-gold fs-12">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= (int)$overallRating[0])
                                                            <i class="bi bi-star-fill"></i>
                                                        @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                                            <i class="bi bi-star-half"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span>({{$product->reviews_count}})</span>
                                            </div>
                                            
                                            


                                            <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4">
                                                    @if($product->discount != 0)
                                                        <del
                                                            class="product__old-price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</del>
                                                    @endif
                                                <ins
                                                    class="product__new-price">{{\App\CPU\Helpers::get_price_range($product) }}</ins>
                                            </div>

                                            <!-- Add to Cart Form -->
                                            <form class="cart add_to_cart_form" action="{{ route('cart.add') }}"
                                                  id="add-to-cart-form" data-redirecturl="{{route('checkout-details')}}"
                                                  data-varianturl="{{ route('cart.variant_price') }}"
                                                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                                                  data-outofstock="{{translate('Sorry').', '.translate('Out_of_stock')}}.">
                                                @csrf
                                                <div class="">
                                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                                    @if (count(json_decode($product->colors)) > 0)
                                                        <div class="d-flex gap-4 flex-wrap align-items-center mb-3">
                                                            <h6 class="fw-semibold">{{translate('color')}}</h6>
                                                            <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2 pt-2">
                                                                @foreach (json_decode($product->colors) as $key => $color)
                                                                    <li>
                                                                        <label>
                                                                            <input type="radio" hidden=""
                                                                                   id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                                                   name="color" value="{{ $color }}"
                                                                                {{ $key == 0 ? 'checked' : '' }}
                                                                            >
                                                                            <span
                                                                                class="color_variants rounded-circle p-0 {{ $key == 0 ? 'color_variant_active':''}}"
                                                                                style="background: {{ $color }};"
                                                                                for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                                                onclick="focus_preview_image_by_color('{{ $key }}')"
                                                                                id="color_variants_{{ $key }}"
                                                                            ></span>
                                                                        </label>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <!--  -->
                                                    @foreach (json_decode($product->choice_options) as $key => $choice)
                                                        <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                                            <h6 class="fw-semibold">{{translate($choice->title)}}</h6>
                                                            <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2">
                                                                @foreach ($choice->options as $key => $option)
                                                                    <li>
                                                                        <label>
                                                                            <input type="radio" hidden=""
                                                                                   id="{{ $choice->name }}-{{ $option }}"
                                                                                   name="{{ $choice->name }}"
                                                                                   value="{{ $option }}"
                                                                                   @if($key == 0) checked @endif >
                                                                            <span>{{$option}}</span>
                                                                        </label>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                    
                                                    <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                                        <h6 class="fw-semibold">{{translate('quantity')}}</h6>

                                                        <div class="quantity quantity--style-two">
                                                            <span class="quantity__minus single_quantity__minus">
                                                                <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                            </span>
                                                            <input type="text"
                                                                   class="quantity__qty product_quantity__qty"
                                                                   name="quantity"
                                                                   value="{{ $product->minimum_order_qty ?? 1 }}"
                                                                   min="{{ $product->minimum_order_qty ?? 1 }}"
                                                                   max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                                            <span class="quantity__plus single_quantity__plus" {{($product->current_stock == 1?'disabled':'')}}>
                                                                <i class="bi bi-plus"></i>
                                                            </span>
                                                        </div>
                                                            @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                                                                <p class="fw-semibold text-muted">{{translate('out_of_stock')}}</p>
                                                            @else
                                                                @if($product['product_type'] == 'physical')
                                                                    <p class="fw-semibold text-muted"><span
                                                                            class="in_stock_status">{{$product->current_stock}}</span> {{translate('in_Stock')}}
                                                                    </p>
                                                                @endif
                                                            @endif
                                                    </div>
                                                    
                                                    
                                                    <div class="mx-w" style="--width: 24rem; margin-bottom: 10px;">
                                                        <div class="bg-light w-100 rounded p-4">
                                                            <div class="flex-between-gap-3">
                                                                <div class="">
                                                                    <h6 class="flex-middle-gap-2 mb-2">
                                                                        <span class="text-muted">{{translate('total_price')}}:</span>
                                                                        <span
                                                                            class="total_price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</span>
                                                                    </h6>
                                                                    <h6 class="flex-middle-gap-2">
                                                                        <span
                                                                            class="text-muted">{{translate('Tax')}}:</span>
                                                                        <span
                                                                            class="product_vat">{{ $product->tax_model == 'include' ? 'incl.' : \App\CPU\Helpers::currency_converter($product->tax)}}</span>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mx-w" style="--width: 24rem;">
                                                        <div class="bg-light w-100 rounded p-4">
                                                            <div class="flex-between-gap-3">
                                                                <p class="fw-bold text-muted" style="font-size: 18px;">
                                                                    <span class="badge bg-primary">{{ $product->views }}</span> {{ translate('views') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   <div class="mx-w d-flex gap-3 mt-4"
                                                         style="--width: 32rem">
                                                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||
                                                        ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))
                                                            <button type="button"
                                                                    class="buy_now_button btn btn-secondary fs-16 flex-grow-1"
                                                                    disabled>{{translate('buy_now')}} {{\App\CPU\Helpers::get_price_range($product) }}</span></button>
                                                            <button type="button"
                                                                    class="update_cart_button btn btn-primary fs-16 flex-grow-1"
                                                                    disabled><i class="bi bi-cart3 fs-16"></i></button>
                                                             @if($shop['id'] != 0)
                                                            <a  href="tel:{{$shop->contact}}" class="update_cart_button btn btn-primary fs-16">{{translate('call_seller')}}</a>
                                                            @endif
                                                            
                                                        @else
                                                            <button type="button"
                                                                    class="buy_now_button btn btn-secondary fs-16"
                                                                    onclick="buy_now('add-to-cart-form', {{(Auth::guard('customer')->check()?'true':'false')}}, '{{route('checkout-details')}}')">{{translate('buy_now')}} {{\App\CPU\Helpers::get_price_range($product) }}</span></button>

                                                            <button type="button"
                                                                    class="update_cart_button btn btn-primary fs-16"
                                                                    onclick="addToCart('add-to-cart-form')"><i class="bi bi-cart3 fs-16"></i></button>
                                                                    
                                                           @if($shop['id'] != 0)
                                                            <a  href="tel:{{$shop->contact}}" class="update_cart_button btn btn-primary fs-16">{{translate('call_seller')}}</a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||
                                                    ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))
                                                        <div class="alert alert-danger mt-3" role="alert">
                                                            {{translate('this_shop_is_temporary_closed_or_on_vacation')}}
                                                            .
                                                            {{translate('You_cannot_add_product_to_cart_from_this_shop_for_now')}}
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <!-- End Product Details Content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <nav>
                                <div class="nav justify-content-center gap-4 nav--tabs" id="nav-tab" role="tablist">
                                    <button class="active" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="true">{{translate("reviews")}}</button>
                                    <button id="product-details-tab" data-bs-toggle="tab" data-bs-target="#product-details" type="button" role="tab" aria-controls="product-details" aria-selected="false">{{translate('Product_Details')}}</button>
                                </div>
                            </nav>
                             <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="reviews" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                                    <div class="details-content-wrap custom-height ov-hidden show-more--content active">
                                        <div class="row gy-4">
                                            <div class="col-lg-5">
                                                <div class="d-flex justify-content-center mt-2">
                                                @if(auth('customer')->check())
                                                
                                                <button class="btn btn-primary rounded-pill" 
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#reviewModalCustomer{{$product->id}}"
                                                                               >{{translate('add_review')}}
                                                </button>
                                                
                                                @else
                                                
                                                <button class="btn btn-primary rounded-pill" 
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#loginModal"
                                                                               >{{translate('add_review')}}
                                                </button>
                                                
                                                @endif
                                                </div>
                                                <div class="rating-review mx-auto text-center mb-30">
                                                    
                                                    <h2 class="rating-review__title"><span
                                                            class="rating-review__out-of">{{round($overallRating[0], 1)}}</span>/5
                                                    </h2>
                                                    
                                                    <div class="rating text-gold mb-2">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= (int)$overallRating[0])
                                                                <i class="bi bi-star-fill"></i>
                                                            @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                                                <i class="bi bi-star-half"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <div class="rating-review__info">
                                                        <span>{{$reviews_of_product->total()}} {{translate('ratings')}}</span>
                                                    </div>
                                                </div>


                                                <ul class="list-rating gap-10">
                                                    <li>
                                                        <span class="review-name">5 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[0] != 0?number_format($rating[0]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="95" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">4 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[1] != 0?number_format($rating[1]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="35" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">3 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[2] != 0?number_format($rating[2]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="35" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">2 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[3] != 0?number_format($rating[3]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="20" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">1 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[4] != 0?number_format($rating[4]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="10" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="d-flex flex-wrap gap-3" id="product-review-list">
                                                    @foreach ($reviews_of_product as $item)
                                                        <div class="card border-primary-light flex-grow-1">
                                                            <div class="media flex-wrap align-items-centr gap-3 p-3">
                                                                <div class="avatar overflow-hidden border rounded-circle"
                                                                     style="--size: 3.437rem">
                                                                    <img
                                                                        src="{{asset("storage/app/public/profile")}}/{{(isset($item->user)?$item->user->image:'')}}"
                                                                        alt=""
                                                                        class="img-fit dark-support"
                                                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                </div>
                                                                <div class="media-body d-flex flex-column gap-2">
                                                                    <div
                                                                        class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                                        <div>
                                                                            <h6 class="mb-1">{{isset($item->user)?$item->user->f_name:translate('User_Not_Exist')}}</h6>
                                                                            <div
                                                                                class="d-flex gap-2 align-items-center">
                                                                                <div
                                                                                    class="star-rating text-gold fs-12">
                                                                                    @for ($inc=0; $inc < 5; $inc++)
                                                                                        @if ($inc < $item->rating)
                                                                                            <i class="bi bi-star-fill"></i>
                                                                                        @else
                                                                                            <i class="bi bi-star"></i>
                                                                                        @endif
                                                                                    @endfor
                                                                                </div>
                                                                                <span>({{$item->rating}}/5)</span>
                                                                            </div>
                                                                        </div>
                                                                        <div>{{$item->updated_at->format("d M Y h:i:s A")}}</div>
                                                                    </div>
                                                                    <p>{{$item->comment}}</p>

                                                                    <div class="d-flex flex-wrap gap-2 products-comments-img">
                                                                        @foreach(json_decode($item->attachment) as $img)
                                                                            @if(file_exists(base_path("storage/app/public/review/".$img)))
                                                                                <a href="{{asset("storage/app/public/review/".$img)}}" data-lightbox="">
                                                                                    <img src="{{asset("storage/app/public/review/".$img)}}" class="remove-mask-img"
                                                                                         onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                                                </a>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    @if(count($product->reviews)==0)
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="text-danger text-center m-0">{{translate('No_reviews_yet')}}</h6>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                

                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        @if(count($product->reviews) > 2)
                                            <button
                                                class="btn btn-outline-primary see-more-details-review m-1 view_text"
                                                onclick="seemore()"
                                                data-productid="{{$product->id}}"
                                                data-routename="{{route('review-list-product')}}"
                                                data-afterextend="{{translate('See_Less')}}"
                                                data-seemore="{{translate('See_More')}}"
                                                data-onerror="{{translate('no_more_review_remain_to_load')}}">{{translate('See_More')}}</button>
                                        @else
                                            <button
                                                class="btn btn-outline-primary see-more-details m-1">{{translate('see_More')}}</button>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="product-details" role="tabpanel" aria-labelledby="product-details-tab" tabindex="0">
                                    <div class="details-content-wrap custom-height ov-hidden show-more--content active">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th class="border-0">{{translate('details_Description')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        @if($product->video_url!=null)
                                                            <div class="col-12 mb-4 text-center">
                                                                <iframe width="560" height="315"
                                                                        src="{{$product->video_url}}">
                                                                </iframe>
                                                            </div>
                                                        @endif
                                                        {!! $product->details !!}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <button
                                            class="btn btn-outline-primary see-more-details">{{translate('See_More')}}</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3 d-flex flex-column gap-3">
                    @if($product->added_by=='seller')
                        @if(isset($product->seller->shop))
                            <div class="card order-1 order-sm-0">
                                <div class="card-body">
                                    <div class="p-2 overlay shop-bg-card"
                                         data-bg-img="{{asset('storage/app/public/shop/banner')}}/{{$product->seller->shop->banner}}">
                                        <div class="media flex-wrap gap-3 p-2">
                                            <div class="avatar border rounded-circle" style="--size: 3.437rem">
                                                <img
                                                    src="{{asset('storage/app/public/shop')}}/{{$product->seller->shop->image}}"
                                                    alt="" class="img-fit dark-support rounded-circle"
                                                    onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                            <div class="media-body d-flex flex-column gap-2 text-absolute-whtie">
                                                <div class="d-flex flex-column gap-1 justify-content-start">
                                                    <h5 class="">{{$product->seller->shop->name}}</h5>
                                                    <div class="d-flex gap-2 align-items-center ">
                                                        <div class="star-rating text-gold fs-12">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= (int)$avg_rating)
                                                                    <i class="bi bi-star-fill"></i>
                                                                @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                                    <i class="bi bi-star-half"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span class="">({{$total_reviews}})</span>
                                                    </div>
                                                    <h6 class="fw-semibold">{{$products_for_review->count()}} {{translate('products')}}</h6>
                                                </div>

                                                <div class="mb-3">
                                                    <div class="text-center d-inline-block">
                                                        <h3 class="mb-1">{{round($rating_percentage)}}%</h3>
                                                        <div class="fs-12">{{translate('positive_review')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (auth('customer')->id() == '')
                                                <div class="btn-circle chat-btn" style="--size: 2.5rem"
                                                     data-bs-toggle="modal" data-bs-target="#loginModal">
                                                    <i class="bi bi-chat-square-dots"></i>
                                                </div>
                                            @else
                                                <div class="btn-circle chat-btn" style="--size: 2.5rem"
                                                     data-bs-toggle="modal" data-bs-target="#contact_sellerModal">
                                                    <i class="bi bi-chat-square-dots"></i>
                                                </div>
                                                
                                            @endif
                                        </div>

                                        <a href="{{ route('shopView',[$product->seller->id]) }}"
                                           class="btn btn-primary btn-block">{{translate('Visit_Store')}}</a>
                                           
                                    </div>
                                    @include('theme-views.layouts.partials.modal._review-customer',['id'=>$product->id,'order_details'=>$product])
                                </div>
                            </div>
                            
                            @include('theme-views.layouts.partials.modal._chat-with-seller',['seller_id'=>$product->seller->id,'shop_id'=>$product->seller->shop->id])

                        @endif
                    @else
                        <div class="card  order-0 order-sm-0">
                            <div class="card-body">
                                <div class="p-2 overlay shop-bg-card"
                                     data-bg-img="{{asset("storage/app/public/shop/")}}/{{ \App\CPU\Helpers::get_business_settings('shop_banner') }}">
                                    <div class="media flex-wrap gap-3 p-2">
                                        <div class="avatar border rounded-circle" style="--size: 3.437rem">
                                            <img
                                                src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}"
                                                alt="" class="img-fit dark-support rounded-circle"
                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                        </div>

                                        <div class="media-body d-flex flex-column gap-2 text-absolute-whtie">
                                            <div class="d-flex flex-column gap-1 justify-content-start">
                                                <h5 class="">{{$web_config['name']->value}}</h5>
                                                <div class="d-flex gap-2 align-items-center ">
                                                    <div class="star-rating text-gold fs-12">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= (int)$avg_rating)
                                                                <i class="bi bi-star-fill"></i>
                                                            @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                                <i class="bi bi-star-half"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>

                                                    <span>({{$total_reviews}})</span>
                                                </div>
                                                <h6 class="fw-semibold">{{$products_for_review->count()}} {{translate('Products')}}</h6>

                                                <div class="mb-3">
                                                    <div class="text-center d-inline-block">
                                                        <h3 class="mb-1">{{round($rating_percentage)}}
                                                            %</h3>
                                                        <div
                                                            class="fs-12">{{translate('positive_review')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('shopView',[0]) }}"
                                       class="btn btn-primary btn-block">{{translate('Visit_Store')}}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card order-1 order-sm-1">
                        <div class="card-body">
                            <h5 class="mb-3">{{translate('More_from_the_Store')}}</h5>

                            <div class="d-flex flex-wrap gap-3">
                                @if (count($more_product_from_seller)>0)
                                    @foreach($more_product_from_seller as $key => $item)
                                        <div class="card border-primary-light flex-grow-1">
                                            <a href="{{route('product',$item->slug)}}"
                                               class="media align-items-centr gap-3 p-3">
                                                <div class="avatar" style="--size: 4.375rem">
                                                    <img
                                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$item['thumbnail']}}"
                                                        alt=""
                                                        class="img-fit dark-support rounded img-fluid overflow-hidden"
                                                        onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                </div>
                                                @php($item_review = \App\CPU\ProductManager::get_overall_rating($item->reviews))

                                                <div class="media-body d-flex flex-column gap-2">
                                                    <h6 class="text-capitalize">{{ Str::limit($item['name'], 18) }}</h6>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <div class="star-rating text-gold fs-12">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= (int)$item_review[0])
                                                                    <i class="bi bi-star-fill"></i>
                                                                @elseif ($item_review[0] != 0 && $i <= (int)$item_review[0] + 1.1 && $item_review[0] > ((int)$item_review[0]))
                                                                    <i class="bi bi-star-half"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span>({{$item->reviews_count}})</span>
                                                    </div>
                                                    <div class="product__price">
                                                        <ins class="product__new-price">
                                                            {{\App\CPU\Helpers::currency_converter(
                                                                $item->unit_price-(\App\CPU\Helpers::get_product_discount($item,$item->unit_price))
                                                            )}}
                                                        </ins>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card border-primary-light flex-grow-1">
                                        <a href="javaScript:void(0)" class="media align-items-centr gap-3 p-3">
                                            <div class="media-body d-flex flex-column gap-2">
                                                <h6>{{translate('similar_product_not_available')}}</h6>
                                            </div>
                                        </a>
                                    </div>

                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Similar Products From Other Stores -->
            <div class="py-4 mt-3">
                <div class="d-flex justify-content-between gap-3 mb-4">
                    <h2>{{translate('similar_Products_From_Other_Stores')}}</h2>
                    <div class="swiper-nav d-flex gap-2 align-items-center">
                        <div class="swiper-button-prev top-rated-nav-prev position-static rounded-10"></div>
                        <div class="swiper-button-next top-rated-nav-next position-static rounded-10"></div>
                    </div>
                </div>
                <div class="swiper-container">
                    <!-- Swiper -->
                    <div class="position-relative">
                        <div class="swiper" data-swiper-loop="false" data-swiper-margin="20" data-swiper-autoplay="true"
                             data-swiper-pagination-el="null" data-swiper-navigation-next=".top-rated-nav-next"
                             data-swiper-navigation-prev=".top-rated-nav-prev"
                             data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "320": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                            <div class="swiper-wrapper">
                                @if (count($relatedProducts)>0)
                                    @foreach($relatedProducts as $key=>$product)
                                        <div class="swiper-slide">
                                            @include('theme-views.partials._similar-product-large-card',['product'=>$product])
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card w-100 px-3 py-4">
                                        <h5 class="text-muted">{{translate('no_similar_products_found')}}.</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </main>

    <!-- End Main Content -->
@endsection

@push('script')

    <script>
        $('.remove-mask-img').on('click', function(){
            $('.show-more--content').removeClass('active')
        })
    </script>

    <script>
        getVariantPrice();
    </script>

    <script src="{{ theme_asset('assets/js/lightbox.min.js') }}"></script>
@endpush
