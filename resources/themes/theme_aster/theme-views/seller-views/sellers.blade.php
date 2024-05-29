@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('All_Stores').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')

    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-30">
        <div class="container">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row gy-2 align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1">{{translate('All_Stores')}}</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb fs-12 mb-0">
                                  <li class="breadcrumb-item"><a href="{{route('home')}}">{{translate('home')}}</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">
                                   <a href="{{route('sellers')}}">{{translate('stores')}}</a></li>
                                </ol>
                              </nav>
                        </div>
                        <div class="col-md-4">
                            <div class="custom_search position-relative float-end">
                                <form action="{{route('search-shop')}}">
                                @csrf
                                <div class="d-flex">
                                    <div class="select-wrap focus-border border border-end-logical-0 d-flex align-items-center">
                                        <input type="search" class="form-control border-0 focus-input search-bar-input"
                                        name="shop_name" placeholder="{{translate('shop_name')}}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                </form>
                                <div class="card search-card __inline-13 position-absolute z-999 bg-white top-100 start-0 search-result-box"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="auto-col xxl-items-6 justify-content-center gap-3">
                        @foreach ($sellers as $shop)
                        @php($current_date = date('Y-m-d'))
                        @php($start_date = date('Y-m-d', strtotime($shop['vacation_start_date'])))
                        @php($end_date = date('Y-m-d', strtotime($shop['vacation_end_date'])))

                        <a href="{{route('shopView',['id'=>$shop['seller_id']])}}" class="store-item grid-center py-2">
                            <div class="position-relative">
                                <div class="avatar rounded-circle border" style="--size: 6.875rem">
                                    <img src="{{asset('storage/app/public/shop/'.$shop->image)}}"
                                    onerror="this.src='{{theme_asset('assets/img/image-place-holder.png')}}'"
                                    alt="{{$shop->name}}" loading="lazy" class="dark-support img-fit rounded-circle">
                                </div>
                                @if($shop->vacation_status && ($current_date >= $start_date) && ($current_date <= $end_date))
                                    <span class="temporary-closed position-absolute rounded-circle">
                                        <span>{{translate('closed_now')}}</span>
                                    </span>
                                @elseif($shop->temporary_close)
                                    <span class="temporary-closed position-absolute rounded-circle">
                                        <span>{{translate('closed_now')}}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex flex-column align-items-center flex-wrap gap-2 mt-3">
                                <h6 class="text-truncate mx-auto text-center">{{Str::limit($shop->name, 14)}}</h6>
                                @php($seller = \App\CPU\ProductManager::get_user_total_product('seller', $shop->seller_id))
                                <p>{{$seller}} {{translate('products')}}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    @if (count($sellers) == 0)
                    <div class="w-100 text-center pt-5">
                        <img width="120" class="mb-3" src="{{ theme_asset('assets/img/not_found.png') }}" alt="">
                        <h5 class="text-center text-muted">{{translate('no_data_found')}}.</h5>
                    </div>
                    @endif
                    <div class="mt-5">
                        {{ $sellers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

@push('script')

@endpush
