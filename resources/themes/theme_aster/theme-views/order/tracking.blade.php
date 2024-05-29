@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('Track_Order_Result ').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="card h-100">
                <div class="card-body py-4 px-sm-4">
                    <div class="mt-4">
                        <h4 class="text-center text-uppercase mb-5">{{ translate('Your_order') }} #{{ $orderDetails['id'] }} {{ translate('is') }}
                            @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                {{translate($orderDetails['order_status'] =='failed' ? 'Failed To Deliver' : $orderDetails['order_status'])}}
                            @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                            @else
                                {{translate($orderDetails['order_status'])}}
                            @endif
                        </h4>
                        <div class="row justify-content-center">
                            <div class="col-xl-10">
                                <div id="timeline">
                                    <div
                                        @if($orderDetails['order_status']=='processing')
                                            class="bar progress two"
                                        @elseif($orderDetails['order_status']=='out_for_delivery')
                                            class="bar progress three"
                                        @elseif($orderDetails['order_status']=='delivered')
                                            class="bar progress four"
                                        @else
                                            class="bar progress one"
                                        @endif
                                    ></div>
                                    <div class="state">
                                        <ul>
                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track1.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="badge active">
                                                    <span>1</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div>
                                                    <div class="state-text">{{translate('Order_placed')}}</div>
                                                    <div class="mt-2 fs-12">{{date('d M, Y h:i A',strtotime($orderDetails->created_at))}}</div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track2.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="{{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                    <span>2</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div>
                                                    <div class="state-text">{{translate('Packaging_order')}}</div>
                                                    @if(($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                        <div class="mt-2 fs-12">
                                                            @if(\App\CPU\order_status_history($orderDetails['id'],'processing'))
                                                            {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($orderDetails['id'],'processing')))}}
                                                            @endif
                                                        </div>
                                                    @endif

                                                </div>
                                            </li>

                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track4.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="{{($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                    <span>3</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div class="state-text">{{translate('Order_is_on_the_way')}}</div>
                                                @if(($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                    <div class="mt-2 fs-12">
                                                        @if(\App\CPU\order_status_history($orderDetails['id'],'out_for_delivery'))
                                                            {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($orderDetails['id'],'out_for_delivery')))}}
                                                        @endif
                                                    </div>
                                                @endif
                                            </li>
                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track5.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="{{($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                    <span>4</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div class="state-text">{{translate('Order_Delivered')}}</div>
                                                @if($orderDetails['order_status']=='delivered')
                                                    <div class="mt-2 fs-12">
                                                        @if(\App\CPU\order_status_history($orderDetails['id'], 'delivered'))
                                                        {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($orderDetails['id'], 'delivered')))}}
                                                        @endif
                                                    </div>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 bg-light p-3 p-sm-4">
                            <h5 class="mb-4">{{ translate('Order_Details') }}</h5>
                            <div class="row gy-3 text-dark track-order-details-info">
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="column-2">
                                            <div>{{ translate('Order_ID') }}</div>
                                            <div class="fw-bold">{{ $orderDetails['id'] }}</div>
                                        </div>
                                        <div class="column-2">
                                            <div>{{ translate('Order_Created_At') }}</div>
                                            <div class="fw-bold">{{date('D ,d M, Y ',strtotime($orderDetails['created_at']))}}</div>
                                        </div>
                                        @if($orderDetails->delivery_man_id && $orderDetails['order_status'] !="delivered")
                                        <div class="column-2">
                                            <div>{{ translate('Estimated_Delivery_Date') }}</div>
                                            <div class="fw-bold">
                                                @if($orderDetails['expected_delivery_date'])
                                                {{date('d M, Y ',strtotime($orderDetails['expected_delivery_date']))}}
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="column-2">
                                            <div>{{ translate('Order_Status') }}</div>
                                            @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status'] =='failed' ? 'Failed To Deliver' : $orderDetails['order_status'])}}
                                                </div>
                                            @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                                                </div>
                                            @else
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status'])}}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="column-2">
                                            <div>{{ translate('Payment_Status') }}</div>
                                            @if($orderDetails['payment_status']=="paid")
                                            <div class="fw-bold">{{ translate('paid') }}</div>
                                            @else
                                                <div class="fw-bold">{{ translate('unpaid') }}</div>
                                            @endif
                                        </div>
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
