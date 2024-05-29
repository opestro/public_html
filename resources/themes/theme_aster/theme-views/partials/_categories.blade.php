<style>
        .swiper-slide img {
            max-width: 100%; /* Make images responsive */
            height: auto; /* Maintain aspect ratio */
            margin: 0 10px; /* Add space between images */
           }
                        </style>
<section>
    <div class="container">
        <div class="">
            <div class="py-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                    <h2 class="text-primary">{{ translate('Catégories') }}</h2>
                    <a href="{{route('products',['data_from'=>'latest'])}}" class="btn-link text-primary">{{ translate('View_All') }}</a>
                </div>
                   
                    <div class="swiper"  
                         data-swiper-navigation-next="#next20" data-swiper-loop="true"  data-swiper-margin="40"
                            data-swiper-navigation-prev="#prev20"  data-swiper-delay="4000"   
                            data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "150": {"slidesPerView": "2"}, "300": {"slidesPerView": "3"}, "450": {"slidesPerView": "4"}, "600": {"slidesPerView": "5"}, "750": {"slidesPerView": "6"}}'>
                            <div class="swiper-wrapper">
                                @if(Session::get('direction') === "rtl")
                                    <div class="swiper-slide" >
                                           <a href="{{route('products',['id'=> 58,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Spare-Parts-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Spare-AR" alt="">
                                            </a>
                                                    
                                       </div>
                                       <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=>70,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Phone&Accesssories-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Phone-AR" alt="">
                                                   </a>  
                                                
                                       </div>
                                       <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=> 90,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Household-Appliances-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Household-AR" alt="">
                                                </a>    
                                       </div>
                                       <div class="swiper-slide" >
                                             <a href="{{route('products',['id'=> 120,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Home-Supplies-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="HomeSupplies-AR" alt="">
                                                 </a>    
                                       </div>
                                       <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=> 107,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Fashion-Accesssories-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support "  id="Fashion-AR"alt="">
                                                  </a>   
                                       </div>
                                       <div class="swiper-slide" >
                                             <a href="{{route('products',['id'=> 78,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Computers&Accesssories-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support "  id="Computers-AR"alt="">
                                                </a>     
                                       </div>
                                       <div class="swiper-slide" >
                                             <a href="{{route('products',['id'=> 98,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Clothing-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support "  id="Clothing-AR"alt="">
                                                </a>     
                                       </div>
                                        <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=> 113,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Beauty-Cosmetics-Dark-AR.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Cosmetics-AR" alt="">
                                                </a>     
                                       </div>
                                       @else
                                       <div class="swiper-slide" >
                                           <a href="{{route('products',['id'=> 58,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Spare-Parts-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Spare-EN" alt="">
                                            </a>
                                                    
                                       </div>
                                       <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=>70,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Phone&Accesssories-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Phone-EN" alt="">
                                                   </a>  
                                                
                                       </div>
                                       <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=> 90,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Household-Appliances-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Household-EN" alt="">
                                                </a>    
                                       </div>
                                       <div class="swiper-slide" >
                                             <a href="{{route('products',['id'=> 120,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Home-Supplies-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="HomeSupplies-EN" alt="">
                                                 </a>    
                                       </div>
                                       <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=> 107,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Fashion-Accesssories-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support "  id="Fashion-EN"alt="">
                                                  </a>   
                                       </div>
                                       <div class="swiper-slide" >
                                             <a href="{{route('products',['id'=> 78,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Computers&Accesssories-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support "  id="Computers-EN"alt="">
                                                </a>     
                                       </div>
                                       <div class="swiper-slide" >
                                             <a href="{{route('products',['id'=> 98,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Clothing-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support "  id="Clothing-EN"alt="">
                                                </a>     
                                       </div>
                                        <div class="swiper-slide" >
                                            <a href="{{route('products',['id'=> 113,'data_from'=>'category','page'=>1])}}">
                                                        <img  src="{{ theme_asset('assets/img/categories-Images/Beauty-Cosmetics-Dark-EN.png') }}"
                                                             onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             loading="lazy" class="img-fit dark-support " id="Cosmetics-EN" alt="">
                                                </a>     
                                       </div>
                                       @endif
                            </div>
                           
                             <div class="swiper-button-next cleaning-nav-next" id="next20"></div>
                             <div class="swiper-button-prev cleaning-nav-prev" id="prev20"></div>
                        </div>
                             
               
            </div>
        </div>
    </div>
    
    

</section>
