<div class="modal-body">
    <div class="product-quickview">
        <button type="button" class="btn-close outside" data-bs-dismiss="modal" aria-label="Close"></button>

        <div class="quickview-content">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5">
                    <!-- Product Details Image Wrap -->
                    <div class="pd-img-wrap position-relative h-100">
                        <div class="swiper-container quickviewSlider2 border rounded" style="--bs-border-color: #d6d6d6">
                            <div class="product__actions d-flex flex-column gap-2">
                                <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
                                class="btn-wishlist add_to_wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist_status == 1?'wishlist_icon_active':'')}}" title="{{translate('add_to_wishlist')}}">
                                    <i class="bi bi-heart"></i>
                                </a>
                                <div class="product-share-icons">
                                    <a href="javascript:" title="Share">
                                        <i class="bi bi-share-fill"></i>
                                    </a>

                                    <ul>
                                        <li>
                                            <a href="javascript:" onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'facebook.com/sharer/sharer.php?u='); return false;">
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'twitter.com/intent/tweet?text='); return false;">
                                                <i class="bi bi-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'linkedin.com/shareArticle?mini=true&url='); return false;">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" onclick="shareOnFacebook('{{route('product',$product->slug)}}', 'api.whatsapp.com/send?text='); return false;">
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
                                            <img src="{{asset("storage/app/public/product/$photo->image_name")}}" class="dark-support rounded" alt=""
                                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                        </div>
                                        @else
                                            <div class="swiper-slide position-relative thumb_{{$key}}" id="thumb_{{$key}}">
                                                @if ($product->discount > 0 && $product->discount_type === "percent")
                                                    <span class="product__discount-badge">-{{$product->discount}}%</span>
                                                @elseif($product->discount > 0)
                                                    <span class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                                @endif
                                                <img src="{{asset("storage/app/public/product/$photo->image_name")}}" class="dark-support rounded" alt=""
                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach (json_decode($product->images) as $key => $photo)
                                        <div class="swiper-slide position-relative thumb_{{$key}}" id="thumb_{{$key}}">
                                            @if ($product->discount > 0 && $product->discount_type === "percent")
                                                <span class="product__discount-badge">-{{$product->discount}}%</span>
                                            @elseif($product->discount > 0)
                                                <span class="product__discount-badge">-{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                            @endif
                                            <img src="{{asset("storage/app/public/product/$photo")}}" class="dark-support rounded" alt=""
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
                                <div class="swiper-wrapper auto-item-width justify-content-center" style="--width: 4rem; --bs-border-color: #d6d6d6">
                                    @if(json_decode($product->colors) && $product->color_image)
                                        @foreach (json_decode($product->color_image) as $key => $photo)
                                            @if($photo->color != null)
                                            <div class="swiper-slide m-2" id="preview-img{{$key}}" onclick="focus_preview_image_by_color('{{$key}}')">
                                                <img src="{{asset("storage/app/public/product/$photo->image_name")}}" class="dark-support rounded border" alt="Product thumb"
                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                            @else
                                            <div class="swiper-slide m-2" onclick="slider_thumb_img_preview('thumb_{{$key}}')">
                                                <img src="{{asset("storage/app/public/product/$photo->image_name")}}" class="dark-support rounded border" alt="Product thumb"
                                                onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                            </div>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach (json_decode($product->images) as $key => $photo)
                                        <div class="swiper-slide m-2" id="preview-img{{$key}}" onclick="slider_thumb_img_preview('thumb_{{$key}}')">
                                            <img src="{{asset("storage/app/public/product/$photo")}}" class="dark-support rounded border"
                                                alt="Product thumb"
                                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                @endif

                                <div class="swiper-button-next swiper-quickview-button-next" style="--size: 1.5rem"></div>
                                <div class="swiper-button-prev swiper-quickview-button-prev" style="--size: 1.5rem"></div>
                            </div>
                        </div>

                    </div>
                    <!-- End Product Details Image Wrap -->
                </div>

                <div class="col-lg-7">
                    <!-- Product Details Content -->
                    <div class="product-details-content position-relative">

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <h2 class="product_title">{{$product['name']}}</h2>

                            @if ($product->discount > 0 && $product->discount_type === "percent")
                                <span class="product__save-amount">{{translate('save')}} {{$product->discount}}%</span>
                            @elseif($product->discount > 0)
                                <span class="product__save-amount">{{translate('save')}} {{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                            @endif

                        </div>

                        <div class="d-flex gap-2 align-items-center mb-2">
                            <div class="star-rating text-gold fs-12">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= (int)$overallRating[0])
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] == ((int)$overallRating[0]+.50))
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span>({{$product->reviews_count}})</span>
                        </div>

                      
                        

                        <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4">
                            @if($product->discount > 0)
                                <del class="product__old-price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</del>
                            @endif
                            <ins class="product__new-price fs-28 mb-n1">{{\App\CPU\Helpers::get_price_range($product) }}</ins>
                        </div>

                        <!-- Add to Cart Form -->
                        <form class="cart add_to_cart_form" id="add-to-cart-form" action="{{ route('cart.add') }}" data-redirecturl="{{route('checkout-details')}}" data-varianturl="{{ route('cart.variant_price') }}" data-errormessage="{{translate('please_choose_all_the_options')}}" data-outofstock="{{translate('Sorry').', '.translate('Out_of_stock')}}.">
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
                                                <span class="color_variants rounded-circle p-0 {{ $key == 0 ? 'color_variant_active':''}}" style="background: {{ $color }};"
                                                for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                onclick="focus_preview_image_by_color('{{ $key }}')" id="color_variants_{{ $key }}"
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
                                                id="{{$choice->name}}-{{$option}}"
                                                name="{{$choice->name}}" value="{{$option}}"
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
                                        <input type="text" class="quantity__qty product_quantity__qty" name="quantity" value="{{ $product->minimum_order_qty ?? 1 }}" min="{{ $product->minimum_order_qty ?? 1 }}" max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                        <span class="quantity__plus single_quantity__plus" {{($product->current_stock == 1?'disabled':'')}}>
                                            <i class="bi bi-plus"></i>
                                        </span>
                                    </div>
                                      @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                                            <p class="fw-semibold text-muted">{{translate('out_of_stock')}}</p>
                                        @else
                                            @if($product['product_type'] == 'physical')
                                            <p class="fw-semibold text-muted"><span class="in_stock_status">{{$product->current_stock}}</span> {{translate('in_Stock')}}</p>
                                            @endif
                                        @endif
                                </div>

                                <div class="bg-light mx-w rounded p-4 "style="margin-bottom: 10px;">
                                    <div class="flex-between-gap-3">
                                        <div class="">
                                            <h6 class="flex-middle-gap-2 mb-2">
                                                <span class="text-muted">{{translate('total_price')}}:</span>
                                                <span class="total_price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</span>
                                            </h6>
                                            <h6 class="flex-middle-gap-2">
                                                <span class="text-muted">{{translate('Tax')}}:</span>
                                                <span class="product_vat">{{\App\CPU\Helpers::currency_converter($product->tax)}}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-light mx-w rounded p-4 ">
                                    <div class="flex-between-gap-3">
                                        <div class="">
                                             <p class="fw-bold text-muted" style="font-size: 18px;">
                                                <span class="badge bg-primary">{{ $product->views }}</span> {{ translate('views') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||
                                    ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))
                                        <button type="button" class="buy_now_button btn btn-secondary fs-16" disabled>{{translate('buy_now')}} {{\App\CPU\Helpers::get_price_range($product) }}</span></button>
                                        <button type="button" class="update_cart_button btn btn-primary fs-16" disabled><i class="bi bi-cart3 fs-16"></i></button>
                                    @else
                                        <button type="button" class="buy_now_button btn btn-secondary fs-16" onclick="buy_now('add-to-cart-form', {{(Auth::guard('customer')->check()?'true':'false')}}, '{{route('checkout-details')}}')">{{translate('buy_now')}} {{\App\CPU\Helpers::get_price_range($product) }}</span></button>

                                        <button type="button" class="update_cart_button btn btn-primary fs-16" onclick="addToCart('add-to-cart-form')"><i class="bi bi-cart3 fs-16"></i></button>
                                    @endif
                                    
                                    @if($product->seller->shop['id'] != 0)
                                    <a  href="tel:{{$product->seller->shop->contact}}" class="btn btn-primary fs-16">{{translate('call_seller')}}</a>
                                    @endif
                                </div>
                                @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||
                                ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))
                                    <div class="alert alert-danger mt-3" role="alert">
                                        {{translate('this_shop_is_temporary_closed_or_on_vacation')}}.
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

<script type="text/javascript">

    $(document).ready(function () {
        getVariantPrice();

        function stock_check_quick_view(){
            minValue = parseInt($('.product_quantity__qty').attr('min'));
            maxValue = parseInt($('.product_quantity__qty').attr('max'));
            valueCurrent = parseInt($('.product_quantity__qty').val());
            let product_qty = $('.product_quantity__qty');

            if (minValue >= valueCurrent) {
                $('.product_quantity__qty').val(minValue);
                product_qty.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
            }else{
                product_qty.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
            }

            if (valueCurrent > maxValue) {
                toastr.warning('Sorry, stock limit exceeded');
                $('.product_quantity__qty').val(maxValue);
            }
            getVariantPrice();
        }

        $('#add-to-cart-form input').on('change', function () {
            stock_check_quick_view();
        });


        $('#add-to-cart-form').on('submit', function (e) {
            e.preventDefault();
        });

        /* Increase */
        $('.single_quantity__plus').on('click', function () {
            var $qty = $(this).parent().find('input');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal)) {
                $qty.val(currentVal + 1);
            }
            if(currentVal >= $qty.attr('max') -1){
                $(this).attr('disabled', true);
            }
            stock_check_quick_view();
        });

        /* Decrease */
        $('.single_quantity__minus').on('click', function () {
            var $qty = $(this).parent().find('input');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                $qty.val(currentVal - 1);
            }
            if (currentVal < $qty.attr('max')) {
                $('.single_quantity__plus').removeAttr('disabled', true);
            }
            stock_check_quick_view();
        });
    });


</script>
