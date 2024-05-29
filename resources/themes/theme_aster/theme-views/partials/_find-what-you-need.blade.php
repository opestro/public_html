<style>
        
.categorie-image-container {
    display: inline-block; 
}

.category-icon {
  width:100%; 
  height: auto; 
  max-height: 210px;
  max-width: 100%;
  border-radius: 50%; 
  margin: 0 10px; 
  overflow: auto;
}

@media (max-width: 767px) {
    .category-name {
        font-size: 14px !important;
    }
}
</style>
<section>
    <div class="container">
        <div class="">
            <div class="py-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                    <h2 class="text-primary">{{ translate('Categories') }}</h2>
                    <a href="{{route('products',['data_from'=>'latest'])}}" class="btn-link text-primary">{{ translate('View_All') }}</a>
                </div>
                   
                    <div class="swiper"  
                         data-swiper-navigation-next="#next20" data-swiper-loop="true"  data-swiper-margin="40"
                            data-swiper-navigation-prev="#prev20"  data-swiper-delay="4000"   
                            data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "150": {"slidesPerView": "2"}, "300": {"slidesPerView": "3"}, "450": {"slidesPerView": "4"}, "600": {"slidesPerView": "5"}, "750": {"slidesPerView": "6"}}'>
                            
                                        <div class="swiper-wrapper">
                                          @foreach($category_slider as $ke=>$all_category)
    						                    @foreach($all_category as $key=>$category)
                                                <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="swiper-slide store-product d-flex flex-column gap-3 align-items-center">
                                                    <div class="categorie-image-container">
                                                          <img src="{{ asset('storage/app/public/category')}}/{{$category['icon']}}" alt="" class="category-icon" loading="lazy">
                                                      </div>
                                                    <div class="d-flex flex-column align-items-center text-center gap-2 w-100">
                                                        <h4 class="category-name text-truncate text-center" style="font-size: 18px; white-space: pre-line;">{{$category['name']}}</h6>
                                                        
                                                    </div>
                                                </a>
                                        		@endforeach
                                        @endforeach
                        </div>
                        <div class="swiper-button-next cleaning-nav-next" id="next20"></div>
                        <div class="swiper-button-prev cleaning-nav-prev" id="prev20"></div>
            </div>
        </div>
    </div>
</div>    
    

</section>
