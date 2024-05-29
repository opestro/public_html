<section class="">
    <div class="container">
        <div class="rounded position-relative">
            @if(Session::get('direction') === "rtl")
                <a href="{{ route('offers') }}" class="d-flex">
                    <img id="bannerImage"
                         src="{{ theme_asset('assets/img/last-banner.png') }}"
                         onerror="this.src='{{ theme_asset('assets/img/banner-placeholder.png') }}'"
                         alt="Arabic Banner" class="img-fluid w-100"
                         style="float: right;" loading="lazy">
                </a>
            @else
                <a href="{{route('products',['data_from'=>'discounted','page'=>1])}}" class="d-flex">
                    <img id="bannerImage"
                         src="{{ theme_asset('assets/img/last-banner.png') }}"
                         onerror="this.src='{{ theme_asset('assets/img/banner-placeholder.png') }}'"
                         alt="English Banner" class="img-fluid w-100"
                         style="float: left;" loading="lazy">
                </a>
            @endif
        </div>
    </div>
</section>
