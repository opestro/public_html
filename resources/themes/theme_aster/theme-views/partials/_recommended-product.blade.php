<section class="py-3">
    <div class="container">
        <h2 class="text-center mb-3">{{ translate('Recommended_For_You') }}</h2>
        <nav class="d-flex justify-content-center">
            <div class="nav nav-nowrap gap-3 gap-xl-5 nav--tabs hide-scrollbar" id="nav-tab" role="tablist">
                <button class="active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#featured_product"
                        role="tab" aria-controls="featured_product">{{ translate('Featured_Products') }}
                </button>
                <button data-bs-toggle="tab" data-bs-target="#best_selling" role="tab"
                        aria-controls="best_selling">{{ translate('Best_Sellings') }}
                </button>
                
            </div>
        </nav>
        <div class="card mt-3">
            <div class="p-2 p-sm-3">
                <div class="tab-content" id="nav-tabContent">
                    <!-- Featured Product -->
                    <div class="tab-pane fade show active" id="featured_product" role="tabpanel" tabindex="0">
                        <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                            <a href="{{route('products',['data_from'=>'featured'])}}" class="btn-link">{{ translate('View_All') }}
                                <i class="bi bi-chevron-right text-primary"></i>
                            </a>
                        </div>
                        <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid" style="--minWidth: 12rem;">
                            <!-- Single Product -->
                            @foreach($featured_products as $product)
                                @if($product)
                                    @include('theme-views.partials._product-large-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Best Selling Product -->
                    <div class="tab-pane fade" id="best_selling" role="tabpanel" tabindex="0">
                        <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                            <a href="{{route('products',['data_from'=>'best-selling'])}}" class="btn-link">{{ translate('View_All') }}
                                <i class="bi bi-chevron-right text-primary"></i>
                            </a>
                        </div>
                        <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid" style="--minWidth: 12rem;">
                            <!-- Single Product -->
                            @foreach($bestSellProduct as $order)
                                @if($order->product)
                                    @include('theme-views.partials._product-large-card',['product'=>$order->product])
                                @endif
                            @endforeach
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</section>
