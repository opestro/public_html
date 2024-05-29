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
                            @include('theme-views.partials._order-details-head',['order'=>$orderDetails])
                            <div class="mt-4 card px-xl-5">
                                <div class="card-body mb-xl-5">
                                    @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                                    <div class="pt-3">
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
                                    <div class="mt-5">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <address class="media gap-2">
                                                    <img width="20" src="{{theme_asset('assets/img/icons/location.png')}}" class="dark-support" alt="">
                                                    <div class="media-body">
                                                        <div class="mb-2 fw-bold fs-16">{{translate('Shipping_Address')}}</div>
                                                        @if($orderDetails->shippingAddress)
                                                            @php($shipping=$orderDetails->shippingAddress)
                                                        @else
                                                            @php($shipping=json_decode($orderDetails['shipping_address_data']))
                                                        @endif
                                                        <p> @if($shipping)
                                                               {{$shipping->address}},<br>
                                                        {{$shipping->city}}
                                                        , {{$shipping->zip}}

                                                        @endif
                                                        </p>
                                                    </div>
                                                </address>
                                            </div>


                                            <div class="col-lg-6">
                                                <address class="media gap-2">
                                                    <img width="20" src="{{theme_asset('assets/img/icons/location.png')}}" class="dark-support" alt="">
                                                    <div class="media-body">
                                                        <div class="mb-2  fw-bold fs-16">{{translate('Billing_Address')}}</div>
                                                        @if($orderDetails->billingAddress)
                                                            @php($billing=$orderDetails->billingAddress)
                                                        @else
                                                            @php($billing=json_decode($orderDetails['billing_address_data']))
                                                        @endif
                                                        <p>
                                                            @if($billing)
                                                                {{ $billing->address ?? '' }}, <br>
                                                                {{ $billing->city ?? '' }}
                                                                , {{ $billing->zip ?? '' }}
                                                            @else
                                                                {{ $shipping->address ?? '' }},<br>
                                                                {{ $shipping->city ?? '' }}
                                                                , {{ $shipping->zip ?? '' }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </address>
                                            </div>


                                        </div>
                                    </div>


                                    @elseif($orderDetails['order_status']=='returned')
                                    <div class="mt-5">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <address class="media gap-2">
                                                    <div class="media-body text-center">
                                                        <div class="mb-2 fw-bold fs-16 badge bg-info rounded-pill">{{translate('Product_Successfully_Returned')}}</div>
                                                    </div>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($orderDetails['order_status']=='canceled')
                                        <div class="mt-5">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <address class="media gap-2">
                                                        <div class="media-body text-center">
                                                            <div class="mb-2 fw-bold fs-16 badge bg-danger rounded-pill">{{translate('order_'.$orderDetails['order_status'])}}</div>
                                                        </div>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    <div class="mt-5">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <address class="media gap-2">
                                                    <div class="media-body text-center">
                                                        <div class="mb-2 fw-bold fs-16 badge bg-danger rounded-pill">{{translate('order_'.$orderDetails['order_status'].'_!_'.'Sorry_we_can`t_complete_your_order')}}</div>
                                                    </div>
                                                </address>
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

