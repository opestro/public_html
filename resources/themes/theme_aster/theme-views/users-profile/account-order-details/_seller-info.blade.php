@extends('theme-views.layouts.app')

@section('title', translate('Order_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-4">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            @include('theme-views.partials._order-details-head',['order'=>$order])
                            <div class="mt-4 card pb-xl-5">
                                <div class="card-body mb-xl-5">
                                    @if($order->seller_is =='seller')
                                    <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                        <div class="media align-items-center gap-3">
                                            <div class="avatar rounded store-avatar">
                                                <img  onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/app/public/shop/'.$order->seller->shop->image)}}" class="dark-support rounded img-fit" alt="">
                                            </div>
                                            <div class="media-body d-flex flex-column gap-2">
                                                <h4>{{$order->seller->shop->name}}</h4>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="star-rating text-gold fs-12">
                                                        @for($inc=1;$inc<=5;$inc++)
                                                            @if ($inc <= (int)$avg_rating)
                                                                <i class="bi bi-star-fill"></i>
                                                                @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                                <i class="bi bi-star-half"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="text-muted fw-semibold">{{number_format($avg_rating,1)}}</span>
                                                </div>
                                                <ul class="list-unstyled list-inline-dot fs-12">
                                                    <li>{{$rating_count}} {{translate('Reviews')}} </li>
                                                </ul>
                                            </div>
                                        </div>
                                        @if(isset($order->seller->shop) && $order->seller->shop['id'] != 0)
                                        <div class="d-flex flex-column gap-3">
                                            <button  class="btn btn-primary"
                                                     data-bs-toggle="modal" data-bs-target="#contact_sellerModal">
                                                <i class="bi bi-chat-square-fill"></i>
                                                {{translate('Chat_with_seller')}}
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    <!--MOdal -->
                                    @if(isset($order->seller->shop) && $order->seller->shop['id'] != 0)
                                        @include('theme-views.layouts.partials.modal._chat-with-seller',['seller_id'=>$order->seller['id'],'shop_id'=>$order->seller->shop['id']])
                                    @endif
                                    <!-- end MOdal -->
                                    <div class="d-flex gap-3 flex-wrap mt-4">
                                        <div class="card flex-grow-1">
                                            <div class="card-body grid-center">
                                                <div class="text-center">
                                                    <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{round($rating_percentage)}}%</h2>
                                                    <p class="text-muted">{{translate('Positive_Review')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card flex-grow-1">
                                            <div class="card-body grid-center">
                                                <div class="text-center">
                                                    <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{$order->seller->product->count()}}</h2>
                                                    <p class="text-muted">{{translate('Products')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                            <div class="media align-items-center gap-3">
                                                <div class="avatar rounded store-avatar">
                                                    <img  class="dark-support rounded img-fit" alt=""
                                                         src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}"
                                                         onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'">
                                                </div>
                                                <div class="media-body d-flex flex-column gap-2">
                                                    <h4> {{$web_config['name']->value}}</h4>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <div class="star-rating text-gold fs-12">
                                                            @for($inc=1;$inc<=5;$inc++)
                                                                @if ($inc <= (int)$avg_rating)
                                                                    <i class="bi bi-star-fill"></i>
                                                                @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                                    <i class="bi bi-star-half"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span class="text-muted fw-semibold">{{number_format($avg_rating,1)}}</span>
                                                    </div>
                                                    <ul class="list-unstyled list-inline-dot fs-12">
                                                        <li>{{$rating_count}} {{translate('Reviews')}} </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 flex-wrap mt-4">
                                            <div class="card flex-grow-1">
                                                <div class="card-body grid-center">
                                                    <div class="text-center">
                                                        <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{round($rating_percentage)}}%</h2>
                                                        <p class="text-muted">{{translate('Positive_Review')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card flex-grow-1">
                                                <div class="card-body grid-center">
                                                    <div class="text-center">
                                                        <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{$product_count}}</h2>
                                                        <p class="text-muted">{{translate('Products')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
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
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: '{{route('messages_store')}}',
                data: $('#chat-form').serialize(),
                success: function (respons) {

                    toastr.success('{{translate('send_successfully')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#chat-form').trigger('reset');
                }
            });

        });
    </script>
@endpush
