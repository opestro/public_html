<!-- Footer -->
<footer class="footer">
    <div class="footer-bg-img" data-bg-img="{{theme_asset('assets/img/background/footer-bg.png')}}">

    </div>
    <div class="footer-top">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-9 col-sm-6 d-flex justify-content-center justify-content-sm-start justify-content-lg-center">

                    <ul class="list-socials list-socials--white gap-4 fs-18">
                        @if($web_config['social_media'])
                            @foreach ($web_config['social_media'] as $item)
                                <li>
                                    <a href="{{$item->link}}" target="_blank">
                                        <i class="bi bi-{{($item->name == 'google-plus'?'google':$item->name)}}"></i>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6 d-flex justify-content-center justify-content-sm-start">
                    <div class="media gap-3 absolute-white">
                        <i class="bi bi-telephone-forward fs-28"></i>
                        <div class="media-body">
                            <h6 class="absolute-white mb-1">{{translate('Hotline')}}</h6>
                            <a href="tel:{{$web_config['phone']->value}}" class="absolute-white">{{$web_config['phone']->value}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-main px-2  px-lg-0">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <div class="widget widget--about text-center text-lg-start absolute-white">
                        <img class="mb-3" width="180" src="{{asset("storage/app/public/company/")}}/{{ $web_config['footer_logo']->value }}"
                        onerror="this.src='{{theme_asset('assets/img/logo-white.png')}}'"
                        loading="lazy" alt="">
                        <p>{{ \App\CPU\Helpers::get_business_settings('shop_address')}}</p>
                        <a href="mailto:{{$web_config['email']->value}}">{{$web_config['email']->value}}</a>

                        <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap mt-4">
                            @if($web_config['android']['status'])
                                <a href="{{ $web_config['android']['link'] }}"><img src="{{ theme_asset('assets/img/media/google-play.png') }}" loading="lazy" alt=""></a>
                            @endif
                            @if($web_config['ios']['status'])
                                <a href="{{ $web_config['ios']['link'] }}"><img src="{{ theme_asset('assets/img/media/app-store.png') }}" loading="lazy" alt=""></a>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row gy-5">
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('Accounts')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    @if($web_config['seller_registration'])
                                        <li>
                                            <a href="{{route('shop.apply')}}">{{translate('Open_Your_Store')}}</a>
                                        </li>
                                    @endif
                                    <li>
                                        @if(auth('customer')->check())
                                            <a href="{{route('user-profile')}}">{{translate('Profile')}}</a>
                                        @else
                                            <button class="bg-transparent border-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('Profile')}}</button>
                                        @endif
                                    </li>
                                    <!--
                                    <li>
                                        @if(auth('customer')->check())
                                            <a href="{{route('track-order.index') }}">{{translate('track_order')}}</a>
                                        @else
                                            <button class="bg-transparent border-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('track_order')}}</button>
                                        @endif
                                    </li>
                                    -->
                                    <li><a href="{{route('contacts')}}">{{translate('Help_&_Support')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('Quick_Links')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    @if($web_config['flash_deals'])
                                        <li><a href="{{route('flash-deals',[$web_config['flash_deals']['id']])}}">{{translate('Flash_Deals')}}</a></li>
                                    @endif
                                    <li><a href="{{route('products',['data_from'=>'featured','page'=>1])}}">{{translate('Featured_Products')}}</a></li>
                                    <li><a href="{{route('special-sellers')}}">{{translate('Special_Stores')}}</a></li>
                                    <li><a href="{{route('products',['data_from'=>'latest'])}}">{{translate('Latest_Products')}}</a></li>
                                    <li style="display: none;"><a href="{{route('helpTopic')}}">{{translate('FAQ')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('Other')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    <li><a href="{{route('about-us')}}">{{translate('About_Company')}}</a></li>
                                    <li><a href="{{route('privacy-policy')}}">{{translate('Privacy_Policy')}}</a></li>
                                    <li><a href="{{route('terms')}}">{{translate('Terms_&_Conditions')}}</a></li>

                                    @if(isset($web_config['refund_policy']['status']) && $web_config['refund_policy']['status'] == 1)
                                        <li style="display: none;">
                                            <a href="{{route('refund-policy')}}">{{translate('refund_policy')}}</a>
                                        </li>
                                    @endif

                                    @if(isset($web_config['return_policy']['status']) && $web_config['return_policy']['status'] == 1)
                                        <li style="display: none;">
                                            <a href="{{route('return-policy')}}">{{translate('return_policy')}}</a>
                                        </li>
                                    @endif

                                    @if(isset($web_config['cancellation_policy']['status']) && $web_config['cancellation_policy']['status'] == 1)
                                        <li style="display: none;">
                                            <a href="{{route('cancellation-policy')}}">{{translate('cancellation_policy')}}</a>
                                        </li>
                                    @endif

                                    <li>
                                        @if(auth('customer')->check())
                                            <a href="{{route('account-tickets')}}">{{translate('Support_Ticket')}}</a>
                                        @else
                                            <button class="bg-transparent border-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('Support_Ticket')}}</button>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
            
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom absolute-white">
        <div class="container">
            <div class="text-center copyright-text">
                {{ $web_config['copyright_text']->value }}
            </div>
        </div>
    </div>
</footer>
