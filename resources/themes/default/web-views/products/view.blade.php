@extends('layouts.front-end.app')

@section('title',\App\CPU\translate($data['data_from']).' '.\App\CPU\translate('products'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="og:title" content="Products of {{$web_config['name']}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="twitter:title" content="Products of {{$web_config['name']}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <style>
        .for-count-value {

        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0.6875 rem;;
        }

        .for-count-value {

        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0.6875 rem;
        }

        .for-brand-hover:hover {
            color: {{$web_config['primary_color']}};
        }

        .for-hover-lable:hover {
            color: {{$web_config['primary_color']}}       !important;
        }

        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}      !important;
        }

        .for-shoting {
            padding- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 9px;
        }

        .sidepanel {
        {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;
        }
        .sidepanel .closebtn {
        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 25 px;
        }
        @media (max-width: 360px) {
            .for-shoting-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0% !important;
            }

            .for-mobile {

                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10% !important;
            }

        }

        @media (max-width: 500px) {
            .for-mobile {

                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 27%;
            }
        }

    </style>
@endpush

@section('content')

@php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
    <!-- Page Title-->
    <div class="d-flex w-100 justify-content-center align-items-center mb-3 __min-h-70px __inline-35" style="background:{{$web_config['primary_color']}}10;">

        <div class="text-capitalize container text-center">
            <span class="__text-18px font-semibold">{{\App\CPU\translate(str_replace('_',' ',$data['data_from']))}} {{\App\CPU\translate('products')}} {{ isset($data['brand_name']) ? '('.$data['brand_name'].')' : ''}}</span>
        </div>

    </div>
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 rtl __inline-35"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
            <aside
                class="col-lg-3 hidden-xs col-md-3 col-sm-4 SearchParameters __search-sidebar {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}}"
                id="SearchParameters">
                <!--Price Sidebar-->
                <div class="cz-sidebar box-shadow-lg __inline-35" id="shop-sidebar">
                    <div class="cz-sidebar-header box-shadow-sm">
                        <button class="close {{Session::get('direction') === "rtl" ? 'mr-auto' : 'ml-auto'}}"
                                type="button" data-dismiss="sidebar" aria-label="Close"><span
                                class="d-inline-block font-size-xs font-weight-normal align-middle"><span class="d-inline-block align-middle"
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="pb-0" >
                        <!-- Filter by price-->
                        <div class="text-center">
                            <div class="__cate-side-title border-bottom">
                                <span class="widget-title font-semibold">{{\App\CPU\translate('filter')}} </span>
                            </div>
                            <div
                                class="__p-25-10 w-100 pt-4">
                                <label class="w-100 opacity-75 text-nowrap for-shoting d-block mb-0" for="sorting" style="padding-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0">
                                    <select class="form-control custom-select" id="searchByFilterValue">
                                        <option selected disabled>{{\App\CPU\translate('Choose')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'best-selling','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='best-selling'?'selected':'':''}}>{{\App\CPU\translate('best_selling_product')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'top-rated','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='top-rated'?'selected':'':''}}>{{\App\CPU\translate('top_rated')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'most-favorite','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='most-favorite'?'selected':'':''}}>{{\App\CPU\translate('most_favorite')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'featured_deal','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='featured_deal'?'selected':'':''}}>{{\App\CPU\translate('featured_deal')}}</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3" >
                        <!-- Filter by price-->
                        <div class="text-center">
                            <div class="__cate-side-title border-top border-bottom">
                                <span class="widget-title font-semibold">{{\App\CPU\translate('Price')}} </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center __cate-side-price">
                                <div class="__w-35p">
                                    <input class="bg-white cz-filter-search form-control form-control-sm appended-form-control"
                                           type="number" value="0" min="0" max="1000000" id="min_price" placeholder="Min">

                                </div>
                                <div class="__w-10p">
                                    <p class="m-0">{{\App\CPU\translate('to')}}</p>
                                </div>
                                <div class="__w-35p">
                                    <input value="100" min="100" max="1000000"
                                           class="bg-white cz-filter-search form-control form-control-sm appended-form-control"
                                           type="number" id="max_price"  placeholder="Max">

                                </div>

                                <div class="d-flex justify-content-center align-items-center __number-filter-btn">

                                    <a class=""
                                        onclick="searchByPrice()">
                                        <i class="__inline-37 czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"></i>
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div>
                        <div class="text-center">
                            <div class="__cate-side-title border-top border-bottom">
                                <span class="widget-title font-semibold">{{\App\CPU\translate('brands')}}</span>
                            </div>
                            <div class="__cate-side-price pb-0">
                                <div class="input-group-overlay input-group-sm">
                                    <input style="{{Session::get('direction') === "rtl" ? 'padding-right: 32px;' : ''}}" placeholder="{{ \App\CPU\translate('search_by_brands') }}"
                                        class="__inline-38 cz-filter-search form-control form-control-sm appended-form-control"
                                        type="text" id="search-brand">
                                    <div class="input-group-append-overlay">
                                        <span class="input-group-text">
                                            <i class="czi-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <ul id="lista1" class="__brands-cate-wrap"
                                data-simplebar data-simplebar-auto-hide="false">
                                @foreach(\App\CPU\BrandManager::get_active_brands() as $brand)
                                    <div class="brand mt-2 for-brand-hover {{Session::get('direction') === "rtl" ? 'mr-2' : ''}}" id="brand">
                                        <li class="flex-between __inline-39"
                                            onclick="location.href='{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}'">
                                            <div >
                                                {{ $brand['name'] }}
                                            </div>
                                            @if($brand['brand_products_count'] > 0 )
                                                <div class="__brands-cate-badge">
                                                    <span class="">
                                                    {{ $brand['brand_products_count'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </li>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="mt-3 __cate-side-arrordion">
                        <!-- Categories-->
                        <div>
                            <div  class="text-center __cate-side-title border-top border-bottom">
                                <span class="widget-title font-semibold">{{\App\CPU\translate('categories')}}</span>
                            </div>
                            @php($categories=\App\CPU\CategoryManager::parents())
                            <div class="accordion mt-n1 __cate-side-price" id="shop-categories">
                                @foreach($categories as $category)
                                    <div >
                                        <div class="card-header p-1 flex-between">
                                            <div>
                                                <label class="for-hover-lable cursor-pointer" onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                                    {{$category['name']}}
                                                </label>
                                            </div>
                                            <div class="px-2 cursor-pointer" onclick="$('#collapse-{{$category['id']}}').slideToggle(300); if($(this).find('.pull-right').hasClass('active')){
                                                $(this).find('.pull-right').removeClass('active');
                                                $(this).find('.pull-right').text('+')
                                            }else {$(this).find('.pull-right').addClass('active');
                                                $(this).find('.pull-right').text('-')}

                                                ">
                                                <strong class="pull-right for-brand-hover">
                                                    {{$category->childes->count()>0?'+':''}}
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="card-body p-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                             id="collapse-{{$category['id']}}"
                                             style="display: none">
                                            @foreach($category->childes as $child)
                                                <div class=" for-hover-lable card-header p-1 flex-between">
                                                    <div>
                                                        <label class="cursor-pointer" onclick="location.href='{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}'">
                                                            {{$child['name']}}
                                                        </label>
                                                    </div>
                                                    <div class="px-2 cursor-pointer" onclick="$('#collapse-{{$child['id']}}').slideToggle(300); if($(this).find('.pull-right').hasClass('active')){
                                                $(this).find('.pull-right').removeClass('active');
                                                $(this).find('.pull-right').text('+')
                                            }else {$(this).find('.pull-right').addClass('active');
                                                $(this).find('.pull-right').text('-')}">
                                                        <strong class="pull-right">
                                                            {{$child->childes->count()>0? '+' : '' }}
                                                        </strong>
                                                    </div>
                                                </div>
                                                <div
                                                    class="card-body p-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                    id="collapse-{{$child['id']}}"
                                                    style="display: none">
                                                    @foreach($child->childes as $ch)
                                                        <div class="card-header p-1">
                                                            <label class="for-hover-lable d-block cursor-pointer text-left"
                                                                   onclick="location.href='{{route('products',['id'=> $ch['id'],'data_from'=>'category','page'=>1])}}'">{{$ch['name']}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </aside>

            <!-- Content  -->
            <section class="col-lg-9">
                <div class="d-flex flex-wrap align-items-center justify-content-between __inline-43 __gap-6px p-2">
                    <div class="filter-show-btn btn btn--primary py-1 px-2">
                        <i class="tio-filter"></i>
                    </div>
                    <h1 class="max-sm-order-1">
                        <label id="price-filter-count"> {{$products->total()}} {{\App\CPU\translate('items found')}} </label>
                    </h1>
                    <div class="d-flex align-items-center ml-auto">

                        <div class="w-100">
                            <form id="search-form" action="{{ route('products') }}" method="GET">
                                <input hidden name="data_from" value="{{$data['data_from']}}">
                                <div class=" {{Session::get('direction') === "rtl" ? 'float-left' : 'float-right'}}">
                                    <label class="for-shoting" for="sorting">
                                        <span>{{\App\CPU\translate('sort_by')}}</span>
                                    </label>
                                    <select class="__inline-44"
                                                onchange="filter(this.value)">
                                        <option value="latest">{{\App\CPU\translate('Latest')}}</option>
                                        <option
                                            value="low-high">{{\App\CPU\translate('Low_to_High')}} {{\App\CPU\translate('Price')}} </option>
                                        <option
                                            value="high-low">{{\App\CPU\translate('High_to_Low')}} {{\App\CPU\translate('Price')}}</option>
                                        <option
                                            value="a-z">{{\App\CPU\translate('A_to_Z')}} {{\App\CPU\translate('Order')}}</option>
                                        <option
                                            value="z-a">{{\App\CPU\translate('Z_to_A')}} {{\App\CPU\translate('Order')}}</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @if (count($products) > 0)
                    <div class="row mt-3" id="ajax-products">
                        @include('web-views.products._ajax-products',['products'=>$products,'decimal_point_settings'=>$decimal_point_settings])
                    </div>
                @else
                    <div class="text-center pt-5">
                        <h2>{{\App\CPU\translate('No Product Found')}}</h2>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "70%";
            document.getElementById("mySidepanel").style.height = "100vh";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

        function filter(value) {
            $.get({
                url: '{{url('/')}}/products',
                data: {
                    id: '{{$data['id']}}',
                    name: '{{$data['name']}}',
                    data_from: '{{$data['data_from']}}',
                    min_price: '{{$data['min_price']}}',
                    max_price: '{{$data['max_price']}}',
                    sort_by: value
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function searchByPrice() {
            let min = $('#min_price').val();
            let max = $('#max_price').val();
            $.get({
                url: '{{url('/')}}/products',
                data: {
                    id: '{{$data['id']}}',
                    name: '{{$data['name']}}',
                    data_from: '{{$data['data_from']}}',
                    sort_by: '{{$data['sort_by']}}',
                    min_price: min,
                    max_price: max,
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                    $('#paginator-ajax').html(response.paginator);
                    console.log(response.total_product);
                    $('#price-filter-count').text(response.total_product + ' {{\App\CPU\translate('items found')}}')
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        $('#searchByFilterValue, #searchByFilterValue-m').change(function () {
            var url = $(this).val();
            if (url) {
                window.location = url;
            }
            return false;
        });

        $("#search-brand").on("keyup", function () {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>li").show().filter(function () {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });
    </script>


@endpush
