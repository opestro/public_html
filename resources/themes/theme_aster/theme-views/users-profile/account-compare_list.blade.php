@extends('theme-views.layouts.app')

@section('title', translate('My_Compare_List').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('My_Compare_List')}}</h5>
                                <div class="d-flex gap-4 flex-wrap">
                                    @if($compare_lists->count()>0)
                                        <a href="javascript:" onclick="route_alert('{{ route('delete-compare-list-all') }}','{{translate('want_to_clear_all_compare_list?')}}')" class="btn-link text-danger">{{translate('Clear_All')}}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="table-responsive">
                                    <table class="table align-middle table-bordered compare--table">
                                        <tbody>
                                        @if($compare_lists->count()>0)
                                        <tr>
                                            <th></th>
                                            @foreach ($compare_lists as $compare_list)
                                                <th>
                                                    <div class="d-flex flex-column gap-1 align-items-center">
                                                        <img width="160" src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$compare_list->product['thumbnail']}}"
                                                        onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" class="dark-support" alt="">
                                                        <a href="javascript:" onclick="route_alert('{{ route('delete-compare-list', ['id'=>$compare_list['id']]) }}','{{translate('want_to_delete_this_item')}}?')" class="btn-link text-danger text-decoration-underline">{{translate('remove')}}</a>
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>{{ translate('Product_Name') }}</th>
                                            @foreach ($compare_lists as $compare_list)
                                                <td>
                                                    <a href="{{route('product',$compare_list->product['slug'])}}">
                                                        {{ $compare_list->product['name'] }}
                                                    </a>
                                                </td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <th>{{ translate('price') }}</th>
                                            @foreach ($compare_lists as $compare_list)
                                                <td>{{ \App\CPU\Helpers::currency_converter($compare_list->product['unit_price']) }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>{{ translate('Brand') }}</th>
                                            @foreach ($compare_lists as $compare_list)
                                                @if ($web_config['brand_setting'])
                                                    @if(isset($compare_list->product->brand->image))
                                                    <td>
                                                        <a href="{{ route('products',['id'=> $compare_list->product->brand['id'],'data_from'=>'brand','page'=>1]) }}">
                                                            <img width="48" src="{{asset("storage/app/public/brand/".$compare_list->product->brand->image)}}"
                                                                 onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" class="rounded dark-support" alt="">
                                                        </a>
                                                    </td>
                                                    @else
                                                        {{ translate('non_brand_product') }}
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>{{ translate('category') }}</th>
                                            @foreach ($compare_lists as $compare_list)
                                                @php($category_id = $compare_list->product['category_id'])
                                                <td>
                                                    <a href="{{route('products',['id'=> $compare_list->product['category_id'],'data_from'=>'category','page'=>1])}}">
                                                    {{ \App\CPU\CategoryManager::get_category_name($compare_list->product['category_id']) }}
                                                    </a>
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endif

                                        @if($compare_lists->count()==0)
                                        <tr>
                                            <td><h5 class="text-center">{{translate('not_found_anything')}}</h5></td>
                                        </tr>
                                        @endif
                                        </tbody>
                                    </table>
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
