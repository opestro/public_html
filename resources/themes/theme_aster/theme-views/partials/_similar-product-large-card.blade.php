@php($overallRating = $product->reviews ? \App\CPU\ProductManager::get_overall_rating($product->reviews) : 0)
<div class="product border rounded text-center d-flex flex-column gap-10" onclick="location.href='{{route('product',$product->slug)}}'">
    <!-- Product top -->
    <div class="product__top" style="--width: 100%; --height: 12.5rem">
        @if($product->discount > 0)
            <span class="product__discount-badge">
                -@if ($product->discount_type == 'percent')
                    {{round($product->discount, $web_config['decimal_point_settings'])}}%
                @elseif($product->discount_type =='flat')
                    {{\App\CPU\Helpers::currency_converter($product->discount)}}
                @endif
            </span>
        @endif

        @if(isset($product->flash_deal_status) && $product->flash_deal_status)
        <div class="product__power-badge">
            <img src="{{ theme_asset('assets/img/svg/power.svg') }}" alt=""
                 class="svg text-white" loading="lazy">
        </div>
        @endif

        @php($wishlist = count($product->wish_list)>0 ? 1 : 0)
        @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
        <div class="product__actions d-flex flex-column gap-2">
            <a href="javascript:" onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')" id="wishlist-{{$product['id']}}"
            class="btn-wishlist stopPropagation add_to_wishlist wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}" title="Add to wishlist">
                <i class="bi bi-heart"></i>
            </a>
            <a href="javascript:" class="btn-compare stopPropagation add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}" title="{{ translate('add_to_compare') }}" onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')" id="compare_list-{{$product['id']}}">
                <i class="bi bi-repeat"></i>
            </a>
            <a href="javascript:" class="btn-quickview stopPropagation" onclick="location.href='{{route('product',$product->slug)}}'" title="Quick View"
              >
                <i class="bi bi-eye"></i>
            </a>
        </div>

        <div class="product__thumbnail">
            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" loading="lazy"
                 class="img-fit dark-support rounded" alt="">
        </div>
        @if(($product['product_type'] == 'physical') && ($product['current_stock'] < 1))
            <div class="product__notify">
                {{ translate('sorry') }}, {{ translate('this_item_is_currently_sold_out') }}
            </div>
        @endif

        @if(isset($product->flash_deal_status) && $product->flash_deal_status)
        <div class="product__countdown d-flex gap-2 gap-sm-3 justify-content-center"
            data-date="{{ $product->flash_deal_end_date }}">
            <div class="days d-flex flex-column gap-2"></div>
            <div class="hours d-flex flex-column gap-2"></div>
            <div class="minutes d-flex flex-column gap-2"></div>
            <div class="seconds d-flex flex-column gap-2"></div>
        </div>
        @endif
    </div>

    <!-- Product Summery -->
    <div class="product__summary d-flex flex-column align-items-center gap-1 pb-3  cursor-pointer">
        <div class="d-flex gap-2 align-items-center">
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
            <span>( {{$product->reviews->count()}} )</span>
        </div>

        <div class="text-muted fs-12">
            @if($product->added_by=='seller')
                {{ isset($product->seller->shop->name) ? \Illuminate\Support\Str::limit($product->seller->shop->name, 20) : '' }}
            @elseif($product->added_by=='admin')
                {{$web_config['name']->value}}
            @endif
        </div>

        <h6 class="product__title text-truncate">
            {{ \Illuminate\Support\Str::limit($product['name'], 25) }}
        </h6>

        <div class="product__price d-flex flex-wrap column-gap-2">
            @if($product->discount > 0)
                <del class="product__old-price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</del>
            @endif
            <ins class="product__new-price">
                {{\App\CPU\Helpers::currency_converter($product->unit_price-\App\CPU\Helpers::get_product_discount($product,$product->unit_price))}}
            </ins>
        </div>
    </div>
</div>
