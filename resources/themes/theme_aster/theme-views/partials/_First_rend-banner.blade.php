<section class="">
    <div class="container">
        <div class="rounded position-relative">
            @if(Session::get('direction') === "rtl")
                <a  href="{{route('shopView',['2722'])}}">
                    <img id="bannerImage"
                         src="{{theme_asset('assets/img/lindo-advertise.jpg')}}"
                         onerror="this.src='{{ theme_asset('assets/img/banner-placeholder.png') }}'"
                         alt="Arabic Banner" class="img-fluid w-100"
                         style="float: right;" loading="lazy">
                </a>
            @else
                <a  href="{{route('shopView',['2722'])}}">
                    <img id="bannerImage"
                         src="{{theme_asset('assets/img/lindo-advertise.jpg')}}"
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
