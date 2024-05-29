
<section>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom gap-3 d-flex align-items-start justify-content-between mb-30">
                    <h3 class="styled-title">{{translate('Home_Supplies_Products')}}</h3>
                    <a href="{{route('products',['id'=> 120,'data_from'=>'category','page'=>1])}}" class="btn-link">{{translate('View_All')}}
                        <i class="bi bi-chevron-right text-primary"></i>
                    </a>
                </div>

                <div class="swiper-container auto-item-width position-relative">
                    <div class="swiper" data-swiper-loop="true" data-swiper-items="auto" data-swiper-margin="20"
                        data-swiper-pagination-el="null" data-swiper-navigation-next="#next31"
                        data-swiper-navigation-prev="#prev31" data-swiper-delay="4000">
                        <div class="swiper-wrapper text-center">
                           @foreach($homeSuppliesProducts as $key=>$product)
                                    
                                    <div class="swiper-slide">
                                        @include('theme-views.partials._product-large-card',['product'=>$product])
                                    </div>
                                    
                                @endforeach
                        </div>
                    </div>
                    <div class="swiper-button-next cleaning-nav-next" id="next31"></div>
                    <div class="swiper-button-prev cleaning-nav-prev" id="prev31"></div>
                </div>
            </div>
        </div>
    </div>
</section>