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
                            <div class="mt-4 card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                        @if($order->delivery_type == 'self_delivery')
                                        <div class="media gap-2 gap-sm-3">
                                            <div class="avatar overflow-hidden rounded store-avatar2">
                                                <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                     src="{{ asset('storage/app/public/delivery-man/'.$order->delivery_man->image)}}" class="dark-support rounded img-fit" alt="">
                                            </div>
                                            <div class="media-body d-flex flex-column gap-2">
                                                <h4>{{$order->delivery_man->f_name}}&nbsp{{$order->delivery_man->l_name}}</h4>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="star-rating text-gold fs-12">
                                                        @php($avg_rating = isset($order->delivery_man->rating[0]->average) ? $order->delivery_man->rating[0]->average : 0)
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
                                                <ul class="list-unstyled list-inline-dot flex-wrap fs-12">
                                                    <li>{{$delivered_count}} {{translate('Delivered_Orders')}}</li>
                                                    <li>{{$order->delivery_man->review_count}} {{translate('Reviews')}} </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap flex-md-column gap-3">
                                            <button data-bs-toggle="modal" data-bs-target="#chatModal" class="btn btn-outline-primary flex-grow-1">
                                                <i class="bi bi-chat-square-fill"></i>
                                                {{translate('Chat_with_delivery')}}
                                            </button>
                                            @if($order->order_type == 'default_type' && $order->order_status=='delivered' && $order->delivery_man_id)
                                            <button  class="btn btn-primary flex-grow-1"
                                                        data-bs-toggle="modal" data-bs-target="#reviewModal">
                                                <i class="bi bi-chat-square-fill"></i>
                                                {{translate('review')}}
                                            </button>
                                            @endif
                                        </div>
                                        <!--MOdal -->
                                        <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content">
                                                        <div class="modal-header px-sm-5">
                                                            <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('Write_something ')}}</h1>

                                                        </div>
                                                        <div class="modal-body px-sm-5">
                                                            <form action="{{route('messages_store')}}" method="post" id="chat-form">
                                                                @csrf
                                                                @if($order->delivery_man->id != 0)
                                                                    <input value="{{$order->delivery_man->id}}" name="delivery_man_id" hidden>
                                                                @endif
                                                                <textarea name="message" class="form-control" required></textarea>
                                                                <br>
                                                                @if($order->delivery_man->id != 0)
                                                                <button class="btn btn-secondary m-0">{{translate('send')}}</button>
                                                                @else
                                                                    <button class="btn btn-secondary m-0"
                                                                            disabled>{{translate('send')}}</button>
                                                                @endif
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer gap-3 pb-4 px-sm-5">
                                                            <a href="{{route('chat',['type' => 'delivery-man'])}}" class="btn btn-secondary m-0">
                                                                {{translate('go_to')}} {{\App\CPU\translate('chatbox')}}
                                                            </a>
                                                            <button type="button" class="btn btn-primary m-0"
                                                                    data-bs-dismiss="modal">{{translate('close')}}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end Chat Modal -->
                                            <!-- Review Modal -->
                                            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header px-sm-5">
                                                            <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('Submit_a_review')}}</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('submit-deliveryman-review')}}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body px-sm-5">
                                                                <div class="form-group mb-4">
                                                                    <label for="rating">{{'Rating'}}</label>
                                                                    <select name="rating" id="rating" class="form-select">
                                                                        <option value="1">{{translate('1')}}</option>
                                                                        <option value="2">{{translate('2')}}</option>
                                                                        <option value="3">{{translate('3')}}</option>
                                                                        <option value="4">{{translate('4')}}</option>
                                                                        <option value="5">{{translate('5')}}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group mb-4">
                                                                    <label for="comment">{{translate('Comment')}}</label>
                                                                    <input name="order_id" value="{{$order->id}}" hidden>

                                                                    <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="{{translate('Leave_a_comment')}}"></textarea>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer gap-3 pb-4 px-sm-5">
                                                                <a href="{{ URL::previous() }}" class="btn btn-secondary m-0" data-bs-dismiss="modal">{{translate('Back')}}</a>
                                                                <button type="submit" class="btn btn-primary m-0">{{translate('Submit')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end Review Modal -->

                                        @elseif($order->delivery_type == 'third_party_delivery')
                                            <div class="media align-items-center gap-3">
                                                <div class="media-body d-flex flex-column gap-2">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <div class="card-body">
                                                            <address>
                                                                <dl class="mb-0 flexible-grid sm-down-1" style="--width: 15rem">
                                                                    <dt>{{translate('delivery_service_name')}}</dt>
                                                                    <dd>{{$order->delivery_service_name}}</dd>
                                                                </dl>
                                                                @if($order->third_party_delivery_tracking_id !=null)
                                                                <dl class="mb-0 flexible-grid sm-down-1" style="--width: 15rem">
                                                                    <dt>{{translate('third_party_delivery_tracking_id')}}</dt>
                                                                    <dd>{{$order->third_party_delivery_tracking_id}}</dd>
                                                                </dl>
                                                                @endif
                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div
                                        @else
                                            <div class="d-flex justify-content-center flex-grow-1">
                                                <div class="d-flex flex-column ">
                                                    <img width="120" src="{{ theme_asset('assets/img/not_found.png') }}" alt="">
                                                    <h6 class="text-center text-danger mt-3">
                                                        {{ translate('no_delivery_man_assigned_yet') }}
                                                    </h6>
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
        </div>
    </main>
    <!-- End Main Content -->






@endsection
@push('script')
    <script>
        function review_message() {
            toastr.info('{{translate('you_can_review_after_the_product_is_delivered!')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function refund_message() {
            toastr.info('{{translate('you_can_refund_request_after_the_product_is_delivered!')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
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

