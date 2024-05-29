<div class="swiper-slide">
    <!-- Single Product -->
    <a href="javascript:"
       class="store-product d-flex flex-column gap-2 align-items-center ov-hidden">
        <div class="store-product__top border rounded mb-2">

            @if(isset($product->flash_deal_status) && $product->flash_deal_status == 1)
            <div class="product__power-badge">
                <img src="{{theme_asset('assets/img/svg/power.svg')}}" alt="" class="svg text-white" loading="lazy">
            </div>
            @endif
            @if($product->discount > 0)
                <span class="product__discount-badge">-
                     @if ($product->discount_type == 'percent')
                        {{round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                    @elseif($product->discount_type =='flat')
                        {{\App\CPU\Helpers::currency_converter($product->discount)}}
                    @endif
                </span>
            @else
            @endif
            <span class="store-product__action preventDefault" onclick="quickView('{{$product->id}}', '{{route('quick-view')}}')">
                <i class="bi bi-eye fs-12"></i>
            </span>

            <img width="155"
                 src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" alt=""
                 loading="lazy" class="dark-support rounded" loading="lazy">
        </div>
        <a class="fs-16 text-truncate text-muted text-capitalize" href="{{route('product',$product->slug)}}" style="--width: 9rem">
            {{ Str::limit($product['name'], 18) }}
            <div class="product__price d-flex justify-content-center align-items-center flex-wrap column-gap-2 mt-1">
                @if($product->discount > 0)
                    <del class="product__old-price">
                        {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                    </del>
                @endif
                <ins class="product__new-price fs-14">
                    {{\App\CPU\Helpers::currency_converter($product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price)))}}
                </ins>
            </div>
        </a>
    </a>
</div>

