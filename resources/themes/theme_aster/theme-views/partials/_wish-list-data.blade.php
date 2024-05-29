<div class="table-responsive d-none d-md-block">
    <table class="table align-middle table-striped">
        <tbody>
        @if($wishlists->count()>0)
            @foreach($wishlists as $key=>$wishlist)
                @php($product = $wishlist->product_full_info)
                @if( $wishlist->product_full_info)
                    <td>
                        <div class="media gap-3 align-items-center mn-w200">
                            <div class="avatar border rounded" style="--size: 3.75rem">
                                <img
                                    src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                    onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                    class="img-fit dark-support rounded" alt="" loading="lazy">
                            </div>
                            <div class="media-body">
                                <a href="{{route('product',$product['slug'])}}">
                                    <h6 class="text-truncate text-capitalize"
                                        style="--width: 20ch">{{$product['name']}}</h6>
                                </a>
                            </div>
                            @if($brand_setting)
                                <div class="media-body">
                                    <h6 class="text-truncate"
                                        style="--width: 10ch">{{$product->brand?$product->brand['name']:''}} </h6>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($product->discount > 0)
                            <del style="color: #E96A6A;">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                            </del> &nbsp;&nbsp;
                        @endif
                        {{\App\CPU\Helpers::get_price_range($product) }}
                    </td>
                    <td>
                        @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
                        <div class="d-flex justify-content-center gap-2 align-items-center">
                            <a href="#"
                               class="btn btn-outline-success rounded-circle btn-action add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
                               onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')"
                               id="compare_list-{{$product['id']}}">
                                <i class="bi bi-repeat"></i>
                            </a>
                            <button type="button"
                                    onclick="removeWishlist({{$product['id']}}, '{{ route('delete-wishlist') }}')"
                                    class="btn btn-outline-danger rounded-circle btn-action">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if($wishlists->count()==0)
            <tr>
                <td><h5 class="text-center">{{translate('not_found_anything')}}</h5></td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<div class="d-flex flex-column gap-2 d-md-none">
    @if($wishlists->count()>0)
        @foreach($wishlists as $key=>$wishlist)
            @php($product = $wishlist->product_full_info)
            @if( $wishlist->product_full_info)
                <div class="media gap-3 bg-light p-3 rounded">
                    <div class="avatar border rounded" style="--size: 3.75rem">
                        <img
                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                            onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                            class="img-fit dark-support rounded" alt="" loading="lazy">
                    </div>
                    <div class="media-body d-flex flex-column gap-1">
                        <a href="{{route('product',$product['slug'])}}">
                            <h6 class="text-truncate text-capitalize" style="--width: 20ch">{{$product['name']}}</h6>
                        </a>
                        <div>
                            {{ translate('price') }} :
                            @if($product->discount > 0)
                                <del style="color: #E96A6A;">
                                    {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                                </del> &nbsp;&nbsp;
                            @endif
                            {{\App\CPU\Helpers::get_price_range($product) }}
                        </div>

                        @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
                        <div class="d-flex gap-2 align-items-center mt-1">
                            <a href="#"
                               class="btn btn-outline-success rounded-circle btn-action add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
                               onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')">
                                <i class="bi bi-repeat"></i>
                            </a>
                            <button type="button" onclick="removeWishlist({{$product['id']}}, '{{ route('delete-wishlist') }}')" class="btn btn-outline-danger rounded-circle btn-action">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>

<div class="card-footer border-0">
    {{$wishlists->links()}}
</div>
