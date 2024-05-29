<style>
    /* CSS to set a specific width for the images */
    .feature-icon-wrap img {
        width: 100%; /* Adjust this value as needed */
        max-width: 100%;
        height: auto;
    }
</style>

<!-- Feature -->
<section class="feature-section">
    <div class="container-fluid py-3 text-center">
        <div class="row justify-content-center">
            
            
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3"> <!-- Added classes here -->
                <div class="media gap-3 align-items-center">
                    <div class="feature-icon-wrap">
                        <img src="{{asset('public/assets/front-end/png/handshake.png')}}" alt="">
                    </div>
                    <div class="media-body"> <!-- Added ml-3 for left margin -->
                        <h5 class="mb-2">{{translate('Contact Guaranteed')}}</h5>
                        <div class="fs-12">{{translate('We_guarantee_contact_between_buyer_and_seller')}}</div>
                    </div>
                </div>
            </div>
           
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3"> <!-- Added classes here -->
                <div class="media gap-3 align-items-center">
                    <div class="feature-icon-wrap">
                        <img src="{{asset('public/assets/front-end/png/Genuine.png')}}" alt="">
                    </div>
                    <div class="media-body">
                        <h5 class="mb-2">{{translate('100% Authentic Products')}}</h5>
                        <div class="fs-12">{{translate('We_Ensure_the_authenticity_of_the_products.')}}</div>
                    </div>
                </div>
            </div>
             
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3"> <!-- Added classes here -->
                <div class="media gap-3 align-items-center">
                    <div class="feature-icon-wrap">
                        <img src="{{theme_asset('assets/img/icons/f4.png')}}" alt="">
                    </div>
                    <div class="media-body">
                        <h5 class="mb-2">{{translate('24/7_Support_Center')}}</h5>
                        <div class="fs-12">{{translate('We_Ensure_Secure_Transactions')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
