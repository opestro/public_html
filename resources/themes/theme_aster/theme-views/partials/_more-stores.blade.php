
@push('css_or_js')

<style>
    .more-store-image-container {
        width: 100px; /* Adjust this value to your desired image size */
        height: 100px; /* Set the same value as width to make it squared */
        overflow: hidden;
        border-radius: 50%; /* To make the images rounded */
    }

    .more-store-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 576px) {
        .more-store-image-container {
            width: 90px; /* Adjust this value for smaller devices */
            height: 90px; /* Set the same value as width to make it squared */
        }
    }
</style>

@endpush
<section>
    <div class="container">
        <div class="card">
            <div class="p-3 p-sm-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 mb-sm-4">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <h2><span class="text-primary">{{ translate('more_stores') }}</span> {{ translate('') }}</h2>
                    </div>
                    <div class="swiper-nav d-flex gap-2 align-items-center">
                        <div class="swiper-button-prev more-stores-nav-prev position-static rounded-10"></div>
                        <div class="swiper-button-next more-stores-nav-next position-static rounded-10"></div>
                    </div>
                </div>
                <div class="swiper-container">
                    <!-- Swiper -->
                    <div class="position-relative">
                        <div class="swiper" data-swiper-loop="true" data-swiper-margin="20"
                             data-swiper-pagination-el="null" data-swiper-navigation-next=".more-stores-nav-next"
                             data-swiper-navigation-prev=".more-stores-nav-prev"
                             data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "150": {"slidesPerView": "2"}, "300": {"slidesPerView": "3"}, "450": {"slidesPerView": "4"}, "600": {"slidesPerView": "5"}, "750": {"slidesPerView": "6"}}'>
                        <div class="swiper-wrapper">
                        @foreach($more_seller as $seller)
                            <a href="{{ route('shopView', ['id' => $seller['id']]) }}" class="swiper-slide store-product d-flex flex-column gap-3 align-items-center">
                                <div class="position-relative more-store-image-container">
                                    <img src="{{ asset('storage/app/public/shop/' . $seller->shop->image) }}" alt=""
                                        onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                        class="dark-support img-fit rounded-circle more-store-image" loading="lazy">
                                </div>

                                <div class="d-flex flex-column align-items-center text-center gap-2 w-100">
                                    <h6 class="text-truncate text-center">{{ $seller->shop->name }}</h6>
                                    <!--<div class="text-muted text-truncate product-count">{{ $seller->product_count }} {{ translate('products') }}</div>-->
                                </div>
                            </a>
                        
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@push('scripts')



<script>
    document.addEventListener('DOMContentLoaded', function () {
        var moreStoresSwiper = new Swiper('.swiper', {
            loop: true,
            navigation: {
                nextEl: '.more-stores-nav-next',
                prevEl: '.more-stores-nav-prev',
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                150: {
                    slidesPerView: 2,
                },
                300: {
                    slidesPerView: 3,
                },
                450: {
                    slidesPerView: 4,
                },
                600: {
                    slidesPerView: 5,
                },
                750: {
                    slidesPerView: 6,
                },
            },
        });
    });
</script>

@endpush

