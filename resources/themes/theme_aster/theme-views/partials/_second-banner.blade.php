<section class="">
    <div class="container">
        <div class="rounded position-relative">
            @if(Session::get('direction') === "rtl")
                <a href="#">
                    <img id="bannerImage"
                         src="{{ theme_asset('assets/img/second-banner-AR.png')}}"
                         onerror="this.src='{{ theme_asset('assets/img/banner-placeholder.png')}}'"
                         alt="Arabic Banner" class="img-fluid w-100"
                         style="float: right;" loading="lazy">
                </a>
            @else
                <a href="#">
                    <img id="bannerImage"
                         src="{{ theme_asset('assets/img/second-banner-EN.png') }}"
                         onerror="this.src='{{ theme_asset('assets/img/banner-placeholder.png') }}'"
                         alt="English Banner" class="img-fluid w-100"
                         style="float: left;" loading="lazy">
                </a>
            @endif
        </div>
    </div>
</section>

<style>
    @media (max-width: 576px) {
        /* Adjust the height for screens smaller than 576px (phones) */
        #bannerImage {
            height: 120px;
        }
    }
</style>
