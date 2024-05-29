<section class="py-3">
    <div class="container">
        <h2 class="text-center mb-3">{{ translate('Latest_Products') }}</h2>
        <nav class="d-flex justify-content-center">
            <div class="nav nav-nowrap gap-3 gap-xl-5 nav--tabs hide-scrollbar" id="nav-tab" role="tablist" style="display: none;">
               
                <button class="active" data-bs-toggle="tab" data-bs-target="#latest_product" role="tab"
                        aria-controls="latest_product">{{ translate('Latest_Products') }}
                </button>
            </div>
        </nav>
        <div class="card mt-3">
            <div class="p-2 p-sm-3">
                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="latest_product" role="tabpanel" tabindex="0">
                        <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                            <a href="{{route('products',['data_from'=>'latest'])}}" class="btn-link">{{ translate('View_All') }}
                                <i class="bi bi-chevron-right text-primary"></i>
                            </a>
                        </div>
                        <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid" style="--minWidth: 12rem;">
                            <!-- Single Product -->
                            @foreach($latest_products as $product)
                                @if($product)
                                    @include('theme-views.partials._product-large-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
