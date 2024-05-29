<section class="bg-primary-light">
    <div class="container">
        <div class="">
            <div class="py-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                    <h2 class="text-primary">{{ translate('Most_viewed_Products') }}</h2>
                    <div class="swiper-nav d-flex gap-2 align-items-center">
                        <div class="swiper-button-prev most-viewed-nav-prev position-static rounded-10"></div>
                        <div class="swiper-button-next most-viewed-nav-next position-static rounded-10"></div>
                    </div>
                </div>
                <div class="swiper-container">
                    <!-- Swiper -->
                    <div class="position-relative">
                        <div class="swiper" data-swiper-loop="true" data-swiper-margin="16"
                             data-swiper-pagination-el="null" data-swiper-navigation-next=".most-viewed-nav-next"
                             data-swiper-navigation-prev=".most-viewed-nav-prev"
                             data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "340": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                            <div class="swiper-wrapper">
                                @foreach($mostViewed as $key=>$product)
                                    
                                    <div class="swiper-slide">
                                        @include('theme-views.partials._product-large-card',['product'=>$product])
                                    </div>
                                    
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
