@extends('theme-views.layouts.app')

@section('title', translate('My_Order_List').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('My_Order_List')}}</h5>
                                <div class="border rounded  custom-ps-3 py-2">
                                    <div class="d-flex gap-2">
                                        <div class="flex-middle gap-2">
                                            <i class="bi bi-sort-up-alt"></i>
                                            <span class="d-none d-sm-inline-block">{{translate('Show_Order_:')}}</span>
                                        </div>
                                        <div class="dropdown">
                                            <button type="button" class="border-0 bg-transparent dropdown-toggle text-dark p-0 custom-pe-3" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{$order_by=='asc'?'Old':'Latest'}}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li >
                                                    <a class="d-flex" href="{{route('account-oder')}}/?order_by=desc">
                                                        {{translate('Latest')}}
                                                    </a>
                                                </li>
                                                <li >
                                                    <a class="d-flex" href="{{route('account-oder')}}/?order_by=asc">
                                                        {{translate('Old')}}
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="table-responsive d-none d-sm-block">
                                    <table class="table align-middle table-striped">
                                        <thead class="text-primary">
                                        <tr>
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('Order_Details')}}</th>
                                            <th class="text-center">{{translate('Status')}}</th>
                                            <th>{{translate('Amount')}}</th>
                                            <th class="text-center">{{translate('Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $key=>$order)
                                        <tr>
                                            <td> {{$orders->firstItem()+$key}}</td>
                                            <td>
                                                <div class="media gap-3 align-items-center mn-w200">
                                                    <div class="avatar rounded" style="--size: 3.75rem">
                                                        @if($order->seller_is == 'seller')
                                                        <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                            src="{{ asset('storage/app/public/shop/'.$order->seller->shop->image)}}" class="img-fit dark-support rounded" alt="">
                                                        @elseif($order->seller_is == 'admin')
                                                            <img  src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}"
                                                                  onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" class="img-fit dark-support rounded" alt="">
                                                        @endif
                                                    </div>
                                                    <div class="media-body">
                                                        <h6>
                                                            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}">{{translate('Order#')}}{{$order['id']}}</a>
                                                        </h6>
                                                        <div class="text-dark fs-12">{{count($order->details)}} {{translate('items')}}</div>
                                                        <p class="text-muted fs-12">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                    <span class="text-center badge bg-danger rounded-pill">
                                                        {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                                                    </span>
                                                @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                    <span class="text-center badge bg-success rounded-pill">
                                                        {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                    </span>
                                                @else
                                                    <span class="text-center badge bg-info rounded-pill">
                                                        {{translate($order['order_status'])}}
                                                    </span>
                                                @endif

                                                <div class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }} mt-1"> {{ translate($order['payment_status']) }}</div>
                                            </td>
                                            <td>{{\App\CPU\Helpers::currency_converter($order['order_amount'])}}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2 align-items-center">
                                                    <a href="{{ route('account-order-details', ['id'=>$order->id]) }}" class="btn btn-outline-info btn-action">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>

                                                    <a href="{{route('generate-invoice',[$order->id])}}" class="btn btn-outline-success btn-action">
                                                        <img src="{{theme_asset('assets/img/svg/download.svg')}}" alt="" class="svg">
                                                    </a>
                                                    @if($order['payment_method']=='cash_on_delivery' && $order['order_status']=='pending')
                                                        <a href="javascript:" title="{{translate('Cancel')}}"
                                                           onclick="route_alert('{{ route('order-cancel',[$order->id]) }}','{{translate('want_to_cancel_this_order?')}}')"
                                                           class="btn btn-danger btn-action">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    @else
                                                        <button class="btn btn-danger btn-action" title="{{\App\CPU\translate('Cancel')}}" onclick="cancel_message()">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if($orders->count()==0)
                                        <div class="mb-2 mt-5 text-center">{{ translate('order_not_found') }} !</div>
                                    @endif

                                    @if($orders->count()>0)
                                        <div class="card-footer border-0">
                                            {{$orders->links() }}
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex flex-column">
                                    @foreach($orders as $key=>$order)
                                    <div class="d-flex gap-2 justify-content-between py-2 border-bottom d-sm-none">
                                        <div class="media gap-2 mn-w200" onclick="location.href='{{ route('account-order-details', ['id'=>$order->id]) }}'">
                                            <div class="avatar rounded" style="--size: 3.75rem">
                                                @if($order->seller_is == 'seller')
                                                <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/app/public/shop/'.$order->seller->shop->image)}}" class="img-fit dark-support rounded" alt="">
                                                @elseif($order->seller_is == 'admin')
                                                    <img  src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}"
                                                            onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" class="img-fit dark-support rounded" alt="">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <h6>{{translate('Order#')}}{{$order['id']}}</h6>
                                                <div class="text-dark fs-12">{{count($order->details)}} {{translate('items')}}</div>
                                                <div class="text-muted fs-12">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</div>
                                                <div class="d-flex gap-2 align-items-center fs-12">
                                                    <div class="text-muted">{{ translate('price') }} : </div>
                                                    <div class="text-dark"> {{\App\CPU\Helpers::currency_converter($order['order_amount'])}}</div>
                                                </div>
                                                <div class="d-flex gap-2 align-items-center fs-12">
                                                    <div class="text-muted">{{ translate('status') }} : </div>
                                                    @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                        <span class="text-center badge bg-danger rounded-pill">
                                                        {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                                                    </span>
                                                    @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                        <span class="text-center badge bg-success rounded-pill">
                                                        {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                    </span>
                                                    @else
                                                        <span class="text-center badge bg-info rounded-pill">
                                                        {{translate($order['order_status'])}}
                                                    </span>
                                                    @endif

                                                    <div class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}"> {{ translate($order['payment_status']) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <a href="{{route('generate-invoice',[$order->id])}}" class="btn btn-outline-success btn-action mb-1">
                                                <img src="{{theme_asset('assets/img/svg/download.svg')}}" alt="" class="svg">
                                            </a>
                                            @if($order['payment_method']=='cash_on_delivery' && $order['order_status']=='pending')
                                                <a href="javascript:" title="{{translate('Cancel')}}"
                                                   onclick="route_alert('{{ route('order-cancel',[$order->id]) }}','{{translate('want_to_cancel_this_order?')}}')"
                                                   class="btn btn-danger btn-action mb-1">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-danger btn-action mb-1" title="{{\App\CPU\translate('Cancel')}}" onclick="cancel_message()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
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
        function cancel_message() {
            toastr.info('{{translate('order_can_be_canceled_only_when_pending.')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>


@endpush
