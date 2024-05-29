@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('Track_Order').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
        <div class="container">
            <div class="card h-100">
                <div class="card-body py-4 px-sm-4">
                    <h2 class="mb-30 text-center">{{ translate('Track_order') }}</h2>
                    <form action="{{route('track-order.result')}}" type="submit" method="post" class="p-sm-3">
                        @csrf
                        <div class="d-flex flex-column flex-sm-row flex-wrap gap-3 align-items-sm-end">
                            <div class="flex-grow-1 d-flex gap-3">
                                <div class="form-group flex-grow-1">
                                    <label for="order_id">{{ translate('Order_ID') }}</label>
                                    <input type="text" id="order_id" name="order_id" class="form-control" placeholder="{{ translate('Order_ID') }}">
                                </div>
                                <div class="form-group flex-grow-1">
                                    <label for="phone_or_email">{{ translate('Phone') }}</label>
                                    <input type="text" id="phone_or_email"  name="phone_number" class="form-control" placeholder="{{ translate('Phone') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary h-45 flex-grow-1">{{ translate('Track_order') }}</button>
                        </div>
                    </form>
                    <div class="text-center mt-5">
                        <img width="92" src="{{ theme_asset('assets/img/media/track-order.png') }}" class="dark-support mb-2" alt="">
                        <p class="text-muted">{{ translate('Enter_your_order_ID_&_phone ') }}<br> {{ translate('to_get_delivery_updates') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection
