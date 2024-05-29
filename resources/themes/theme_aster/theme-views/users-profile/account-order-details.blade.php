@extends('theme-views.layouts.app')

@section('title', translate('Order_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="d-lg-none align-items-center mt-2 mb-3">
                        <div class="d-flex gap-3 justify-content-start mt-2">
                            <h6>{{translate('Order_Status')}}</h6>

                            @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                <span class="badge bg-danger rounded-pill">
                                {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                            </span>
                            @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                <span class="badge bg-success rounded-pill">
                                {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                            </span>
                            @else
                                <span class="badge bg-info rounded-pill">
                                {{translate($order['order_status'])}}
                            </span>
                            @endif
                        </div>

                        <div class="d-flex gap-3 justify-content-start mt-2">
                            <h6>{{translate('Payment_Status')}}</h6>
                            <div
                                class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}"> {{ translate($order['payment_status']) }}</div>
                        </div>
                    </div>
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            @include('theme-views.partials._order-details-head',['order'=>$order])
                            <div class="mt-4 card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @php($digital_product = false)
                                        @foreach ($order->details as $key=>$detail)
                                            @if(isset($detail->product->digital_product_type))
                                                @php($digital_product = $detail->product->product_type == 'digital' ? true : false)
                                                @if($digital_product == true)
                                                    @break
                                                @else
                                                    @continue
                                                @endif
                                            @endif
                                        @endforeach
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                            <tr>
                                                <th class="border-0">{{translate('Product_Details')}}</th>
                                                <th class="border-0 text-center">{{translate('Qty')}}</th>
                                                <th class="border-0 text-end">{{translate('Unit_Price')}}</th>
                                                <th class="border-0 text-end">{{translate('Discount')}}</th>
                                                <th class="border-0 text-end" {{ ($order->order_type == 'default_type' && $order->order_status=='delivered') ? 'colspan="2"':'' }}>{{translate('Total')}}</th>
                                                @if($order->order_type == 'default_type' && ($order->order_status=='delivered' || ($order->payment_status == 'paid' && $digital_product)))
                                                    <th class="border-0 text-center">{{translate('Action')}}</th>
                                                @elseif($order->order_type != 'default_type' && $order->order_status=='delivered')
                                                    <th class="border-0 text-center"></th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($order->details as $key=>$detail)
                                                @php($product=json_decode($detail->product_details,true))
                                                @if($product)
                                                    <tr>
                                                        <td>
                                                            <div class="media gap-3">
                                                                <div
                                                                    class="avatar avatar-xxl rounded border overflow-hidden">
                                                                    <img class="d-block img-fit"
                                                                         onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                                         src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                                                         alt="VR Collection" width="60">
                                                                </div>
                                                                <div class="media-body d-flex gap-1 flex-column">
                                                                    <h6>
                                                                        <a href="{{route('product',[$product['slug']])}}">
                                                                            {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                                                        </a>
                                                                        @if($detail->refund_request == 1)
                                                                            <small> ({{translate('refund_pending')}}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 2)
                                                                            <small> ({{translate('refund_approved')}}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 3)
                                                                            <small> ({{translate('refund_rejected')}}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 4)
                                                                            <small> ({{translate('refund_refunded')}}
                                                                                ) </small> <br>
                                                                        @endif<br>
                                                                    </h6>
                                                                    @if($detail->variant)
                                                                        <small>{{translate('variant')}}
                                                                            :{{$detail->variant}} </small>

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{$detail->qty}}</td>

                                                        <td class="text-end">{{\App\CPU\Helpers::currency_converter($detail->price)}} </td>
                                                        <td class="text-end">{{\App\CPU\Helpers::currency_converter($detail->discount)}}</td>

                                                        <td class="text-end">{{\App\CPU\Helpers::currency_converter(($detail->qty*$detail->price)-$detail->discount)}}</td>

                                                        @php($order_details_date = $detail->created_at)
                                                        @php($length = $order_details_date->diffInDays($current_date))
                                                        <td>
                                                            <div class="d-flex justify-content-center gap-2">
                                                                @if($detail->product && $order->payment_status == 'paid' && $detail->product->digital_product_type == 'ready_product')
                                                                    <a href="{{ route('digital-product-download', $detail->id) }}"
                                                                       class="btn btn-primary rounded-pill mb-1"
                                                                       data-bs-toggle="tooltip"
                                                                       data-bs-placement="bottom"
                                                                       data-bs-title="{{translate('Download')}}">
                                                                        <i class="bi bi-download"></i>
                                                                    </a>
                                                                @elseif($detail->product && $order->payment_status == 'paid' && $detail->product->digital_product_type == 'ready_after_sell')
                                                                    @if($detail->digital_file_after_sell)
                                                                        <a href="{{ route('digital-product-download', $detail->id) }}"
                                                                           class="btn btn-primary rounded-pill mb-1"
                                                                           data-bs-toggle="tooltip"
                                                                           data-bs-placement="bottom"
                                                                           data-bs-title="{{translate('Download')}}">
                                                                            <i class="bi bi-download"></i>
                                                                        </a>
                                                                    @else
                                                                        <span class="btn btn-success mb-1 opacity-half cursor-auto"
                                                                              data-bs-toggle="tooltip"
                                                                              data-bs-placement="bottom"
                                                                              data-bs-title="{{translate('Product_not_uploaded_yet')}}">
                                                                            <i class="bi bi-download"></i>
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="d-flex justify-content-center gap-2">
                                                                @if($order->order_type == 'default_type')
                                                                    @if($order->order_status=='delivered')
                                                                        <button class="btn btn-primary rounded-pill"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#reviewModal{{$detail->id}}">{{translate('Review')}}</button>
                                                                        @include('theme-views.layouts.partials.modal._review',['id'=>$detail->id,'order_details'=>$detail,])
                                                                        
                                                                        <?php
                                                                        /*
                                                                        @if($detail->refund_request !=0)
                                                                            <a class="btn btn-outline-primary rounded-pill text-nowrap"
                                                                               href="{{route('refund-details',[$detail->id])}}">{{translate('refund_details')}}</a>
                                                                        @endif
                                                                        
                                                                        @if( $length <= $refund_day_limit && $detail->refund_request == 0)
                                                                            <button
                                                                                class="btn btn-outline-primary rounded-pill"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#refundModal{{$detail->id}}">{{translate('Refund')}}</button>
                                                                            @include('theme-views.layouts.partials.modal._refund',['id'=>$detail->id,'order_details'=>$detail,'order'=>$order,'product'=>$product])
                                                                        @endif
                                                                        
                                                                        */
                                                                        ?>
                                                                    @endif
                                                                @else
                                                                    <label
                                                                        class="badge bg-info rounded-pill">{{translate('pos_order')}}</label>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @php($summary=\App\CPU\OrderManager::order_summary($order))
                                            <?php
                                            if ($order['extra_discount_type'] == 'percent') {
                                                $extra_discount = ($summary['subtotal'] / 100) * $order['extra_discount'];
                                            } else {
                                                $extra_discount = $order['extra_discount'];
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row justify-content-end mt-2">
                                        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-10">
                                            <div class="d-flex flex-column gap-3 text-dark">
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('Item')}}</div>
                                                    <div>{{$order->details->count()}}</div>
                                                </div>
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('Subtotal')}}</div>
                                                    <div>{{\App\CPU\Helpers::currency_converter($summary['subtotal'])}}</div>
                                                </div>
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('tax_fee')}}</div>
                                                    <div>{{\App\CPU\Helpers::currency_converter($summary['total_tax'])}}</div>
                                                </div>
                                                @if($order->order_type == 'default_type')
                                                    <div
                                                        class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                        <div>{{translate('Shipping')}} {{translate('Fee')}}</div>
                                                        <div>{{\App\CPU\Helpers::currency_converter($summary['total_shipping_cost'])}}</div>
                                                    </div>
                                                @endif
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('Discount')}} {{translate('on_product')}}</div>
                                                    <div> {{\App\CPU\Helpers::currency_converter($summary['total_discount_on_product'])}}</div>
                                                </div>
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('Coupon')}} {{translate('Discount')}}</div>
                                                    <div>
                                                        -{{\App\CPU\Helpers::currency_converter($order->discount_amount)}}</div>
                                                </div>
                                                @if($order->order_type != 'default_type')
                                                    <div
                                                        class="d-flex flex-wrap justify-content-between align-`item`s-center gap-2">
                                                        <div>{{translate('extra')}} {{translate('Discount')}}</div>
                                                        <div>
                                                            -{{\App\CPU\Helpers::currency_converter($extra_discount)}}</div>
                                                    </div>
                                                @endif
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <h4>{{translate('Total')}}</h4>
                                                    <h2 class="text-primary">{{\App\CPU\Helpers::currency_converter($order->order_amount)}}</h2>
                                                </div>

                                            </div>
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

@push('script')
    <script src="{{ theme_asset('assets/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'fileUpload[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                placeholderImage: {
                    image: '{{ theme_asset('assets/img/image-place-holder.png') }}',
                    width: '100%'
                },
                dropFileLabel: "{{ translate('drop_here') }}",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate('input_png_or_jpg')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate('file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(function () {
            $(".coba_refund").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ theme_asset('assets/img/image-place-holder.png') }}',
                    width: '100%'
                },
                dropFileLabel: "{{translate('drop_here')}}",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate('input_png_or_jpg')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate('file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
