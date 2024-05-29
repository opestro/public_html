<section>
    <div class="container">
        <div class="rounded p-4 bg-light position-relative">

            <div class="row">

                <!-- First Card -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title">{{\App\CPU\translate('Total_orders')}}</h5>
                                <img src="{{asset('/public/assets/back-end/img/orders.png')}}" class="business-analytics__img" alt="">
                            </div>
                            <p class="card-text">{{ $stats->orders }}</p>
                        </div>
                    </div>
                </div>

                <!-- Second Card -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title">{{\App\CPU\translate('Total_visitors')}}</h5>
                                <img src="{{asset('/public/assets/back-end/img/viewers.png')}}" class="business-analytics__img" alt="">
                            </div>
                            <p class="card-text">{{ $stats->viewers }}</p>
                        </div>
                    </div>
                </div>

                <!-- Third Card -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                 <h5 class="card-title">{{\App\CPU\translate('Total_stores')}}</h5>
                                <img src="{{asset('/public/assets/back-end/img/shops.png')}}" class="business-analytics__img" alt="">
                            </div>
                            <p class="card-text">{{ $stats->stores }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
