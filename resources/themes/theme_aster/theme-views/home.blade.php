@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('Online Shopping').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/8.3.1/css/shepherd.min.css" />
    <style>
    .shepherd-element, .shepherd-step {
         z-index: 9999 !important; /* Adjust this value as needed */
    }
</style>
@endpush

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3">
       
       
        <!-- Main Banner -->
        @include('theme-views.partials._main-banner')

        <!-- Flash Deal -->
        @if ($web_config['flash_deals'])
            @include('theme-views.partials._flash-deals')
        @endif
        
        
        <!-- Secondary Banner -->
        @include('theme-views.partials._First_rend-banner')
        
       
        
         @include('theme-views.partials._latest-products')
         
         @include('theme-views.partials._fifth-banner')   
        
        @include('theme-views.partials._computer-products')
        @include('theme-views.partials._clothes-products')    
        @include('theme-views.partials._cosmetics-products')  
        @include('theme-views.partials._entertainment-products')  
        @include('theme-views.partials._home-supplies-products')  
        @include('theme-views.partials._phone-accessories-products')  
        @include('theme-views.partials._telephones-products') 
        
        <!-- Top Most Viewed Products -->
        @include('theme-views.partials._most-viewed-products')
        
         <!-- Secondary Banner -->
        @include('theme-views.partials._fourth-banner')    
         <!-- Recommended For You -->
        @include('theme-views.partials._recommended-product')
        
        
        @include('theme-views.partials._all-banners')
        
        @include('theme-views.partials._special-stores')
       
           <!-- Top Stores -->
        @if ($web_config['business_mode'] == 'multi' && count($top_sellers) > 0)
            @include('theme-views.partials._top-stores')
        @endif
        
       
        <!-- More Stores -->
        @if($web_config['business_mode'] == 'multi')
            @include('theme-views.partials._more-stores')
        @endif

       
        <!-- Featured Deals -->
        @if ($web_config['featured_deals']->count()>0)
            @include('theme-views.partials._featured-deals')
        @endif
        
       

        <!-- Top Rated Products -->
        @include('theme-views.partials._top-rated-products')
        <!-- Secondary Banner -->
         

        <!-- Today’s Best Deal an Just for you -->
        @include('theme-views.partials._best-deal-just-for-you')

        <!-- Home Categories -->
        @include('theme-views.partials._home-categories')


       

        
       
        <!-- Call To Action -->
        @if (isset($main_section_banner))
        <section class="">
            <div class="container">
                <div class="py-5 rounded position-relative">
                    <img src="{{asset('storage/app/public/banner')}}/{{$main_section_banner ? $main_section_banner['photo'] : ''}}"
                         onerror="this.src='{{theme_asset('assets/img/main-section-banner-placeholder.png')}}'"
                         alt="" class="rounded position-absolute dark-support img-fit start-0 top-0 index-n1 flipX-in-rtl" loading="lazy">
                    <div class="row justify-content-center">
                        <div class="col-10 py-4">
                            <h6 class="text-primary mb-2">{{ translate('Don’t_Miss_Todays_Deal') }}!</h6>
                            <h2 class="fs-2 mb-4 absolute-dark">{{ translate('Let’s_Shopping_Today') }}</h2>
                            <div class="d-flex">
                                <a href="{{$main_section_banner ? $main_section_banner->url:''}}" class="btn btn-primary fs-16">{{ translate('Shop_Now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>
@endsection



<script>
    @if(Session::get('device') !== 'mobile')
        // Check if the browser supports notifications
        if (!("Notification" in window)) {
            console.log("This browser does not support desktop notification");
        }
        else if (Notification.permission === 'default') {
            window.addEventListener('load', function () {
                Notification.requestPermission().then(function (permission) {
                });
            });
        }
    @endif
</script>

<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('firebase-messaging-sw.js')
    .then((registration) => {
        console.log('Service Worker registered with scope:', registration.scope);
    }).catch((err) => {
        console.log('Service worker registration failed: ', err);
    });
}

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/8.3.1/js/shepherd.min.js"></script>

