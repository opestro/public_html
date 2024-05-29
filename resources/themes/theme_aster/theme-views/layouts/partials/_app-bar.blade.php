<style>
    
.centered-items {
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>
<ul class="list-unstyled d-flex justify-content-around gap-3 mb-0 position-relative bg-white shadow-lg centered-items">
    <li>
        <a href="{{ route('home') }}" class="d-flex align-items-center flex-column py-2 {{ (Request::is('/') || Request::is('home')) ? 'active' : '' }}">
            <i class="bi bi-house-door fs-16"></i>
            <span class="fs-14">{{ translate('Home') }}</span>
        </a>
    </li>
    <li>
        
        <a href="{{route('categories')}}" class="d-flex align-items-center flex-column  py-2">
            <div class="position-relative">
                <i class="bi bi-list fs-16"></i>
            </div>
            <span class="fs-14">{{ translate('Categories') }}</span>
        </a>
        

    </li>
    



    <li>
        <div class="dropup position-static">
            <a
                href="#"
                class="d-flex align-items-center flex-column py-2 {{ Request::is('shop-cart') ? 'active' : '' }}"
                data-toggle="collapse"
                data-target="cart_dropdown"
            >
                <div class="position-relative">
                     <i class="bi bi-cart3 fs-16"></i>
                    @php($cart_mobile=\App\CPU\CartManager::get_cart())
                    <span class="count top-0">{{$cart_mobile->count()}}</span>
                </div>
                <span class="fs-14">{{translate('Cart')}}</span>
            </a>

            <ul class="dropdown-menu scrollY-60 z-n1"
                id="cart_dropdown"
                style="--bs-dropdown-min-width: 100%">
                @if($cart_mobile->count() > 0)
                @php($sub_total=0)
                @php($total_tax=0)
                @foreach($cart_mobile as  $cartItem)
                @php($product=\App\Model\Product::active()->find($cartItem['product_id']))
                <li>
                    <div class="media gap-3">
                        <div class="avatar avatar-xxl overflow-hidden">
                            <img
                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                            onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                             loading="lazy" alt="Product" class="img-fit dark-support rounded img-fit overflow-hidden" />
                        </div>
                        <div class="media-body">
                            <h6 class="mb-2">
                                <a href="{{route('product',$cartItem['slug'])}}">{{Str::limit($cartItem['name'],30)}}</a>
                            </h6>
                            <div class="d-flex gap-3 justify-content-between align-items-end">
                                <div class="d-flex flex-column gap-1">
                                    <div class="fs-12"><span class="cart_quantity_{{ $cartItem['id'] }}">{{$cartItem['quantity']}}</span> × {{\App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount']))}}</div>
                                    <div class="product__price d-flex flex-wrap gap-2">
                                        @if($product->discount > 0)
                                            <del class="product__old-price quantity_price_of_{{ $cartItem['id'] }}">{{\App\CPU\Helpers::currency_converter($cartItem['price']*$cartItem['quantity'])}}</del>
                                        @endif
                                        <ins class="product__new-price discount_price_of_{{ $cartItem['id'] }}">{{\App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])}}</ins>
                                    </div>
                                </div>

                                <div class="quantity">
                                    <span class="quantity__minus cart_quantity__minus{{ $cartItem['id'] }}"  onclick="updateCartQuantity('{{ $cartItem['id'] }}','{{ $cartItem['product_id'] }}', '-1', 'minus')">
                                        <i class="{{ $cartItem['quantity'] == ($product ? $product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                                    </span>
                                    <input type="text" class="quantity__qty cart_quantity_of_{{ $cartItem['id'] }}" data-min="{{ $product ? $product->minimum_order_qty : 1 }}" value="{{$cartItem['quantity']}}"
                                           onchange="updateCartQuantity('{{ $cartItem['id'] }}','{{ $cartItem['product_id'] }}', '0')">
                                    <span class="quantity__plus" onclick="updateCartQuantity('{{ $cartItem['id'] }}','{{ $cartItem['product_id'] }}', '1')">
                                        <i class="bi bi-plus"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @php($sub_total+=($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])
                @php($total_tax+=$cartItem['tax']*(int)$cartItem['quantity'])
                @endforeach

                <li>
                    <div class="flex-between-gap-3 pt-2 pb-4">
                        <h6>{{translate('total')}}</h6>
                        <h3 class="text-primary cart_total_amount">{{\App\CPU\Helpers::currency_converter($sub_total)}}</h3>
                    </div>
                    <div class="d-flex gap-3">
                        @if(auth('customer')->check())
                            <a href="{{route('shop-cart')}}" class="btn btn-outline-primary btn-block">{{translate('view_cart')}}</a>
                            <a href="{{route('checkout-details')}}" class="btn btn-primary btn-block">{{translate('go_to_checkout')}}</a>
                        @else
                            <button class="btn btn-outline-primary btn-block" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('view_cart')}}</button>
                            <button class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('go_to_checkout')}}</button>
                        @endif
                    </div>
                </li>
                @else
                    <div class="widget-cart-item">
                        <h6 class="text-danger text-center m-0 p-2"><i
                                class="fa fa-cart-arrow-down"></i> {{translate('empty_Cart')}}
                        </h6>
                    </div>
                @endif
            </ul>
        </div>
    </li>
    <li>
        @if(auth('customer')->check())
        <a href="{{route('user-profile')}}" class="d-flex align-items-center flex-column py-2">
            <div class="position-relative">
                <i class="bi bi-person fs-16"></i>
                
            </div>
            <span class="fs-14">{{ translate('My_Account') }}</span>
        </a>
        @else
        <a href="javascript:" class="d-flex align-items-center flex-column py-2" data-bs-toggle="modal"
        data-bs-target="#loginModal">
            <div class="position-relative">
               <i class="bi bi-person fs-16"></i>

            </div>
            <span class="fs-14">{{ translate('My_Account') }}</span>
        </a>
        @endif
    </li>
</ul>
