@extends('theme-views.layouts.app')

@section('title', translate('Refund_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset('assets/css/lightbox.min.css') }}">
@endpush

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">

                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-5">
                            <div class="mb-4">
                                <h1 class="modal-title fs-5" id="refundModalLabel">{{translate('Refund_Details')}}</h1>
                            </div>
                            <div class="modal-body">
                                <form action="#">
                                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-4 justify-content-between mb-4">
                                        <div class="media align-items-center gap-3">
                                            <div class="avatar avatar-xxl rounded border overflow-hidden">
                                                <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                     src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}" class="dark-support img-fit rounded" alt="">
                                            </div>
                                            <div class="media-body d-flex gap-1 flex-column">
                                                <h6 class="text-truncate" style="--width: 20ch">
                                                    <h6 >
                                                        <a href="{{route('product',[$product['slug']])}}">
                                                            {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                                        </a>
                                                        @if($order_details->refund_request == 1)
                                                            <small class="text-warning"> ({{translate('refund_pending')}}) </small> <br>
                                                        @elseif($order_details->refund_request == 2)
                                                            <small class="text-primary"> ({{translate('refund_approved')}}) </small> <br>
                                                        @elseif($order_details->refund_request == 3)
                                                            <small class="text-danger"> ({{translate('refund_rejected')}}) </small> <br>
                                                        @elseif($order_details->refund_request == 4)
                                                            <small class="text-success"> ({{translate('refund_refunded')}}) </small> <br>
                                                        @endif<br>
                                                    </h6>
                                                    @if($order_details->variant)
                                                        <small>{{translate('variant')}} :{{$order_details->variant}} </small>

                                                    @endif</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-1 fs-12">
                                            <span>{{ translate('QTY') }} : {{\App\CPU\Helpers::currency_converter($order_details->qty)}}</span>
                                            <span>{{ translate('price') }} : {{\App\CPU\Helpers::currency_converter($order_details->price)}}</span>
                                            <span>{{ translate('discount') }} : {{\App\CPU\Helpers::currency_converter($order_details->discount)}}</span>
                                            <span>{{ translate('tax') }} : {{\App\CPU\Helpers::currency_converter($order_details->tax)}}</span>
                                        </div>

                                        <?php
                                        $total_product_price = 0;
                                        foreach ($order->details as $key => $or_d) {
                                            $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
                                        }
                                        $refund_amount = 0;
                                        $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

                                        $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

                                        $refund_amount = $subtotal - $coupon_discount;
                                        ?>

                                        <div class="d-flex flex-column gap-1 fs-12">
                                            <span>{{translate('Subtotal')}}: {{\App\CPU\Helpers::currency_converter($subtotal)}}</span>
                                            <span>{{translate('Coupon_discount')}}: {{\App\CPU\Helpers::currency_converter($coupon_discount)}}</span>
                                            <span>{{translate('Total_refundable_amount')}}:{{\App\CPU\Helpers::currency_converter($refund_amount)}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <h6  class="mb-2" for="comment">{{translate('Refund_reason')}}</h6>
                                        <p>{{$refund->refund_reason}}</p>
                                    </div>
                                    <div class="form-group">
                                        <h6 class="mb-2">{{translate('Attachment')}}</h6>
                                        <div class="d-flex flex-column gap-3">
                                            @if ($refund->images !=null)
                                                <div class="gallery">
                                                    @foreach (json_decode($refund->images) as $key => $photo)
                                                        <a href="{{asset('storage/app/public/refund')}}/{{$photo}}" class="lightbox_custom">
                                                            <img src="{{asset('storage/app/public/refund')}}/{{$photo}}" alt="" class="img-w-h-100"
                                                                 onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p>{{\App\CPU\translate('no_attachment_found')}}</p>
                                            @endif

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

@push('script')
    <script>
        getVariantPrice();
    </script>

    <script src="{{ theme_asset('assets/js/lightbox.min.js') }}"></script>
@endpush
