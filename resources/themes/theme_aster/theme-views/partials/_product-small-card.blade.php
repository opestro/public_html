@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))

<div class="product border rounded text-center d-flex flex-column gap-10" onclick="location.href='{{route('product',$product->slug)}}'">
    <!-- Product top -->
    <div class="product__top" style="--width: 100%; --height: 12.5rem">
        @if($product->discount > 0)
            <span class="product__discount-badge">-
                @if ($product->discount_type == 'percent')
                    {{round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                @elseif($product->discount_type =='flat')
                    {{\App\CPU\Helpers::currency_converter($product->discount)}}
                @endif
            </span>
        @endif
        <div class="product__actions d-flex flex-column gap-2">
            @php($wishlist = count($product->wish_list)>0 ? 1 : 0)
            @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
            <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
               class="btn-wishlist stopPropagation add_to_wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
               title="{{translate('add_to_wishlist')}}">
                <i class="bi bi-heart"></i>
            </a>
            <a href="javascript:"
               class="btn-compare stopPropagation add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
               onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')"
               id="compare_list-{{$product['id']}}" title="Add to compare">
                <i class="bi bi-repeat"></i>
            </a>
            <a href="javascript:" class="btn-quickview stopPropagation"
               onclick="quickView('{{$product->id}}', '{{route('quick-view')}}')" title="{{translate('Quick_View')}}"
            >
                <i class="bi bi-eye"></i>
            </a>
        </div>

        <div class="product__thumbnail">
            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 loading="lazy" class="img-fit dark-support rounded"
                 onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'" alt="{{ $product['name'] }}">
        </div>
    </div>

    <!-- Product Summery -->
    <div class="product__summary d-flex flex-column align-items-center gap-1 pb-3">
        <div class="d-flex gap-2 align-items-center">
            <span class="star-rating text-gold fs-12">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= (int)$overallRating[0])
                        <i class="bi bi-star-fill"></i>
                    @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] == ((int)$overallRating[0]+.50))
                        <i class="bi bi-star-half"></i>
                    @else
                        <i class="bi bi-star"></i>
                    @endif
                @endfor
            </span>

            <span>({{$product->reviews_count}})</span>
        </div>

        <div class="text-muted fs-12">
            @if($product->added_by=='seller')
                {{ isset($product->seller->shop->name) ? \Illuminate\Support\Str::limit($product->seller->shop->name, 20) : '' }}
            @elseif($product->added_by=='admin')
                {{$web_config['name']->value}}
            @endif
        </div>

        <h6 class="product__title text-truncate" style="--width: 80%">
            <a href="{{route('product',$product->slug)}}"
               class="text-capitalize">{{ Str::limit($product['name'], 23) }}</a>
        </h6>

        <a href="{{route('product',$product->slug)}}">
            <div class="product__price d-flex flex-wrap column-gap-2">
                @if($product->discount > 0)
                    <del class="product__old-price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</del>
                @endif
                <ins class="product__new-price">
                    {{\App\CPU\Helpers::currency_converter(
                        $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                    )}}
                </ins>
            </div>
        </a>
    </div>
</div>
