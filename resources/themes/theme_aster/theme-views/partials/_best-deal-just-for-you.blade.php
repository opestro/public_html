<section>
    <div class="container">
        <div class="row g-3">
            @if(isset($deal_of_the_day->product))
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="p-30">
                        @php($overall_rating = \App\CPU\ProductManager::get_overall_rating($deal_of_the_day->product->reviews))
                        <div class="today-best-deal d-flex justify-content-between gap-2 gap-sm-3">
                            <div class="d-flex flex-column gap-1">
                                <div class="mb-3 mb-sm-4">
                                    <div class="sub-title text-muted mb-1">{{ translate('Don’t_Miss_the_Chance') }} !</div>
                                    <h2 class="title text-primary fw-extra-bold">{{ translate('Today’s_Best_Deal') }}</h2>
                                </div>
                                <div class="mb-3 mb-sm-4 d-flex flex-column gap-1">
                                    <h6 class="text-capitalize">{{ \Illuminate\Support\Str::limit($deal_of_the_day->product->name,30) }}</h6>
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="star-rating text-gold fs-12">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $overall_rating[0])
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif ($overall_rating[0] != 0 && $i <= $overall_rating[0] + 1.1)
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span>({{ $deal_of_the_day->product->reviews->count() }})</span>
                                    </div>

                                    <div class="product__price d-flex flex-wrap align-items-end gap-2 mt-2">
                                        @if($deal_of_the_day->product->discount > 0)
                                            <del class="product__old-price">{{\App\CPU\Helpers::currency_converter($deal_of_the_day->product->unit_price)}}</del>
                                        @endif
                                        <ins class="product__new-price">
                                            {{ \App\CPU\Helpers::currency_converter($deal_of_the_day->product->unit_price-\App\CPU\Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price)) }}
                                        </ins>
                                        <span class="product__save-amount">{{ translate('save') }}
                                            {{ \App\CPU\Helpers::currency_converter(\App\CPU\Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <a href="{{route('product',$deal_of_the_day->product->slug)}}" class="btn btn-primary">{{ translate('Buy_Now') }}</a>
                                </div>
                            </div>
                            <div class="text-center">
                                <img width="309" src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$deal_of_the_day->product->thumbnail}}" alt=""
                                     onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                     class="dark-support rounded" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="{{isset($deal_of_the_day->product) ? 'col-lg-6':'col-lg-12' }}">
                <div class="card h-100">
                    <div class="p-30">
                        <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 align-items-center">
                            <h3 class="mb-1 text-primary"><span >{{translate('')}}</span> {{translate('for_you')}}</h3>

                        </div>
                        <div class="auto-col just-for-you gap-3">
                                    @foreach($just_for_you as $key => $product)
                                        <a href="{{ route('product', $product->slug) }}" class="hover-zoom-in d-flex flex-column gap-2 align-items-center">
                                            <div class="position-relative">
                                                @if($product->discount > 0)
                                                    <span class="product__discount-badge">-
                                                    @if ($product->discount_type == 'percent')
                                                        {{ round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings : 0)) }}%
                                                    @elseif($product->discount_type =='flat')
                                                        {{ \App\CPU\Helpers::currency_converter($product->discount) }}
                                                    @endif
                                                </span>
                                                @endif
                        
                                                <div class="image-container">
                                                    <img width="100" src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}"
                                                         onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                         alt=""
                                                         loading="lazy" class="dark-support rounded image-square" loading="lazy">
                                                </div>
                                            </div>
                                            <div class="product__price d-flex flex-wrap justify-content-center column-gap-2">
                                                @if($product->discount > 0)
                                                    <del class="product__old-price">
                                                        {{ \App\CPU\Helpers::currency_converter($product->unit_price) }}
                                                    </del>
                                                @endif
                                                <ins class="product__new-price">
                                                    {{ \App\CPU\Helpers::currency_converter($product->unit_price - (\App\CPU\Helpers::get_product_discount($product, $product->unit_price))) }}
                                                </ins>
                                            </div>
                                        </a>
                                    @endforeach
                                    
                                    <style>
                                        .image-container {
                                            width: 100px; /* Adjust this value to your desired image size */
                                            height: 100px; /* Set the same value as width to make it squared */
                                            overflow: hidden;
                                            position: relative;
                                        }
                        
                                        .image-square {
                                            width: 100%;
                                            height: 100%;
                                            object-fit: cover;
                                        }
                                    </style>
                                    
                                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
