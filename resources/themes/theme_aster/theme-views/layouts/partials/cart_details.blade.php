@php
    $shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method');
    $cart = \App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get()->groupBy('cart_group_id');
@endphp
<div class="container">
    <h4 class="text-center mb-3">{{ translate('Cart_List') }}</h4>
    <form action="#">
        <div class="row gy-3">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-30">
                            <ul class="cart-step-list">
                                <li class="current"><span><i class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                <li><span><i class="bi bi-check2"></i></span> {{ translate('Shopping_Details') }}</li>
                                <li><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                            </ul>
                        </div>
                        @if(count($cart)==0)
                            @php $physical_product = false; @endphp
                        @endif

                        @foreach($cart as $group_key=>$group)
                            @php
                                $physical_product = false;
                                foreach ($group as $row) {
                                    if ($row->product_type == 'physical') {
                                        $physical_product = true;
                                    }
                                }
                            @endphp

                            @foreach($group as $cart_key=>$cartItem)
                                @if ($shippingMethod=='inhouse_shipping')
                                        <?php

                                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

                                        ?>
                                @else
                                        <?php
                                        if ($cartItem->seller_is == 'admin') {
                                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                        } else {
                                            $seller_shipping = \App\Model\ShippingType::where('seller_id', $cartItem->seller_id)->first();
                                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                        }
                                        ?>
                                @endif

                                @if($cart_key==0)
                                    <div class="bg-primary-light py-2 px-2 px-sm-3 mb-3 mb-sm-4">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            @if($cartItem->seller_is=='admin')
                                                <a href="{{route('shopView',['id'=>0])}}">
                                                    <h5 class="">{{\App\CPU\Helpers::get_business_settings('company_name')}}</h5>
                                                </a>
                                            @else
                                                <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}">
                                                    <h5 class="">{{ \App\CPU\get_shop_name($cartItem['seller_id']) }}</h5>
                                                </a>
                                            @endif

                                            @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                @php
                                                    $choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first()
                                                @endphp

                                                @if(isset($choosen_shipping)==false)
                                                    @php $choosen_shipping['shipping_method_id']=0 @endphp
                                                @endif

                                                @php
                                                    $shippings=\App\CPU\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is'])
                                                @endphp

                                                @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                <div class="border bg-white rounded custom-ps-3">
                                                    <div class="shiiping-method-btn d-flex gap-2">
                                                        <div class="flex-middle flex-nowrap fw-semibold text-dark gap-2">
                                                            <i class="bi bi-truck"></i>
                                                            {{ translate('Shipping_Method') }}:
                                                        </div>
                                                        <div class="dropdown">
                                                            <select class="button border-0 form-control text-dark p-0"
                                                                    onchange="set_shipping_id(this.value,'{{$cartItem['cart_group_id']}}')">
                                                                <option>{{translate('choose_shipping_method')}}</option>
                                                                @foreach($shippings as $shipping)
                                                                    <option value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                                        {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="table-responsive d-none d-sm-block">
                                @php
                                    $physical_product = false;
                                    foreach ($group as $row) {
                                        if ($row->product_type == 'physical') {
                                            $physical_product = true;
                                        }
                                    }
                                @endphp
                                <table class="table align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="border-0">{{ translate('product_details') }}</th>
                                        <th class="border-0 text-center">{{ translate('qty') }}</th>
                                        <th class="border-0 text-end">{{ translate('unit_price') }}</th>
                                        <th class="border-0 text-end">{{ translate('discount') }}</th>
                                        <th class="border-0 text-end">{{ translate('total') }}</th>
                                        @if ( $shipping_type != 'order_wise')
                                        <th class="border-0 text-end">{{ translate('shipping_cost') }} </th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($group as $cart_key=>$cartItem)
                                        <tr>
                                            <td>
                                                <div class="media align-items-center gap-3">
                                                    <div class="avatar avatar-xxl rounded border">
                                                        <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                                                             class="dark-support img-fit rounded img-fluid overflow-hidden" alt="">
                                                    </div>
                                                    <div class="media-body d-flex gap-1 flex-column">
                                                        <h6 class="text-truncate text-capitalize" style="--width: 20ch">
                                                            <a href="{{route('product',$cartItem['slug'])}}">{{$cartItem['name']}}</a>
                                                        </h6>
                                                        @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                                            <div class="fs-12">{{$key1}} : {{$variation}}</div>
                                                        @endforeach
                                                        <div class="fs-12">{{ translate('Unit_Price') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['price']) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php($minimum_order=\App\CPU\ProductManager::get_product($cartItem['product_id']))
                                                <div class="quantity quantity--style-two d-inline-flex">
                                                    <span class="quantity__minus " onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty }}', '{{$cartItem['id']}}', '-1', '{{ $cartItem['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}')">
                                                        <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                                                    </span>
                                                    <input type="text" class="quantity__qty" value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]" id="cartQuantity{{$cartItem['id']}}"
                                                           onchange="updateCartQuantityList('{{ $minimum_order->minimum_order_qty }}', '{{$cartItem['id']}}', '0')" data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">
                                                    <span class="quantity__plus" onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty }}', '{{$cartItem['id']}}', '1')">
                                                        <i class="bi bi-plus"></i>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-end">{{ \App\CPU\Helpers::currency_converter($cartItem['price']*$cartItem['quantity']) }}</td>
                                            <td class="text-end">{{ \App\CPU\Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</td>
                                            <td class="text-end">{{ \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</td>
                                            <td>
                                                @if ( $shipping_type != 'order_wise')
                                                    {{ \App\CPU\Helpers::currency_converter($cartItem['shipping_cost']) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Static Markup -->
                            <div class="d-flex flex-column d-sm-none">
                                @foreach($group as $cart_key=>$cartItem)
                                <div class="border-bottom d-flex align-items-start justify-content-between gap-2 py-2">
                                    <div class="media gap-2">
                                        <div class="avatar avatar-lg rounded border">
                                            <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                                                    class="dark-support img-fit rounded img-fluid overflow-hidden" alt="">
                                        </div>
                                        <div class="media-body d-flex gap-1 flex-column">
                                            <h6 class="text-truncate text-capitalize" style="--width: 20ch">
                                                <a href="{{route('product',$cartItem['slug'])}}">{{$cartItem['name']}}</a>
                                            </h6>
                                            @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                                <div class="fs-12">{{$key1}} : {{$variation}}</div>
                                            @endforeach
                                            <div class="fs-12">{{ translate('Unit_Price') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['price']*$cartItem['quantity']) }}</div>
                                            <div class="fs-12">{{ translate('discount') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</div>
                                            <div class="fs-12">{{ translate('total') }} : {{ \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</div>
                                            @if ( $shipping_type != 'order_wise')
                                            <div class="fs-12">{{ translate('shipping_cost') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['shipping_cost']) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    @php($minimum_order=\App\CPU\ProductManager::get_product($cartItem['product_id']))
                                    <div class="quantity quantity--style-two flex-column d-inline-flex">
                                        <span class="quantity__minus " onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty }}', '{{$cartItem['id']}}', '-1', '{{ $cartItem['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}')">
                                            <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                                        </span>
                                        <input type="text" class="quantity__qty" value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]" id="cartQuantity{{$cartItem['id']}}"
                                                onchange="updateCartQuantityList('{{ $minimum_order->minimum_order_qty }}', '{{$cartItem['id']}}', '0')" data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">
                                        <span class="quantity__plus" onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty }}', '{{$cartItem['id']}}', '1')">
                                            <i class="bi bi-plus"></i>
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        @endforeach

                        @if($shippingMethod=='inhouse_shipping')
                                <?php
                                $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                ?>
                            @if ($shipping_type == 'order_wise' && $physical_product)
                                @php($shippings=\App\CPU\Helpers::get_shipping_methods(1,'admin'))
                                @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                                @if(isset($choosen_shipping)==false)
                                    @php($choosen_shipping['shipping_method_id']=0)
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-control text-dark" onchange="set_shipping_id(this.value,'all_cart_group')">
                                            <option>{{\App\CPU\translate('choose_shipping_method')}}</option>
                                            @foreach($shippings as $shipping)
                                                <option
                                                    value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                    {{$shipping['title']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if( $cart->count() == 0)
                            <div class="d-flex justify-content-center align-items-center">
                                <h4 class="text-danger text-capitalize">{{ translate('cart_empty') }}</h4>
                            </div>
                        @endif

                        <form  method="get">
                            <div class="form-group mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="phoneLabel" class="form-label input-label">{{translate('order_note')}} <span
                                                class="input-label-secondary">({{translate('Optional')}})</span></label>
                                        <textarea class="form-control w-100" rows="5" id="order_note" name="order_note">{{ session('order_note')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order summery Content -->
            @include('theme-views.partials._order-summery')
        </div>
    </form>
</div>

@push('script')
<script>
    cartQuantityInitialize();

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }

    function updateCartQuantityList(minimum_order_qty, key, incr, e) {
        let quantity = parseInt($("#cartQuantity" + key).val())+parseInt(incr);
        let ex_quantity = $("#cartQuantity" + key);
        if(minimum_order_qty > quantity && e != 'delete' ) {
            toastr.error('{{translate("minimum_order_quantity_cannot_be_less_than_")}}' + minimum_order_qty);
            $("#cartQuantity" + key).val(minimum_order_qty);
            return false;
        }

        if (ex_quantity.val() == ex_quantity.data('min') && e == 'delete') {
            $.post("{{ route('cart.remove') }}", {
                _token: '{{ csrf_token() }}',
                key: key
            },
            function (response) {
                updateNavCart();
                toastr.info("{{translate('Item has been removed from cart ')}}", {
                    CloseButton: true,
                    ProgressBar: true
                });
                let segment_array = window.location.pathname.split('/');
                let segment = segment_array[segment_array.length - 1];
                if (segment === 'checkout-payment' || segment === 'checkout-details') {
                    location.reload();
                }
                $('#cart-summary').empty().html(response.data);
            });
        }else{
            $.post('{{route('cart.updateQuantity')}}', {
                _token: '{{csrf_token()}}',
                key,
                quantity
            }, function (response) {
                if (response.status == 0) {
                    toastr.error(response.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $("#cartQuantity" + key).val(response['qty']);
                } else {
                    if (response['qty'] == ex_quantity.data('min')) {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
                    } else {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
                    }
                    updateNavCart();
                    $('#cart-summary').empty().html(response);
                }
            });
        }
    }

    function checkout() {
        let order_note = $('#order_note').val();
        //console.log(order_note);
        $.post({
            url: "{{route('order_note')}}",
            data: {
                _token: '{{csrf_token()}}',
                order_note: order_note,

            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                let url = "{{ route('checkout-details') }}";
                location.href = url;

            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }
</script>
@endpush
