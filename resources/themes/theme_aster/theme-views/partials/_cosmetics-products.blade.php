
<section>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom gap-3 d-flex align-items-start justify-content-between mb-30">
                    <h3 class="styled-title">{{translate('Cosmetics_Products')}}</h3>
                    <a href="{{route('products',['id'=> 113,'data_from'=>'category','page'=>1])}}" class="btn-link">{{translate('View_All')}}
                        <i class="bi bi-chevron-right text-primary"></i>
                    </a>
                </div>

                <div class="swiper-container auto-item-width position-relative">
                    <div class="swiper" data-swiper-loop="true" data-swiper-items="auto" data-swiper-margin="20"
                        data-swiper-pagination-el="null" data-swiper-navigation-next="#next29"
                        data-swiper-navigation-prev="#prev29" data-swiper-delay="4000">
                        <div class="swiper-wrapper text-center">
                           @foreach($cosmeticsProducts as $key=>$product)
                                    
                                    <div class="swiper-slide">
                                        @include('theme-views.partials._product-large-card',['product'=>$product])
                                    </div>
                                    
                                @endforeach
                        </div>
                    </div>
                    <div class="swiper-button-next cleaning-nav-next" id="next29"></div>
                    <div class="swiper-button-prev cleaning-nav-prev" id="prev29"></div>
                </div>
            </div>
        </div>
    </div>
</section>