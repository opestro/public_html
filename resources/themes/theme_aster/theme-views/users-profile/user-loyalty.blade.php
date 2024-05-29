@extends('theme-views.layouts.app')

@section('title', translate('My_Loyalty_Point').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card mb-md-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between gap-2">
                                <h5 class="mb-4">{{translate('Loyalty_Point')}}</h5>
                                <span class="text-dark d-md-none" data-bs-toggle="modal" data-bs-target="#instructionModal"><i class="bi bi-info-circle"></i></span>
                            </div>

                            <div class="d-flex flex-column flex-md-row gap-4 justify-content-center">
                                <div class="wallet-card pb-3 rounded-10 ov-hidden mn-w loyalty-point-card" data-bg-img="{{ theme_asset('assets/img/media/loyalty-card.png') }}">
                                    <div class="card-body d-flex flex-column gap-2 absolute-white">
                                        <img width="34" src="{{theme_asset('assets/img/icons/loyalty-point.png')}}" alt="" class="dark-support">
                                        <h2 class="fs-36 absolute-white"> {{$total_loyalty_point}}</h2>
                                        <p>{{translate('Total_Points')}}</p>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="d-none d-md-block">
                                        <h6 class="mb-3">{{translate('How_to_use')}}</h6>
                                        <ul>
                                            <li>{{translate('Convert_your_loyalty_point_to_wallet_money.')}}</li>
                                            <li>{{translate('Minimum')}} {{App\CPU\Helpers::get_business_settings('loyalty_point_minimum_point')}} {{translate('points_required_to_convert')}} <br>{{translate('into_currency')}}</li>
                                        </ul>
                                    </div>
                                    <div class="d-flex justify-content-center justify-content-md-start">
                                        @if ($wallet_status == 1 && $loyalty_point_status == 1)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#convertToCurrency">
                                                {{ translate('convert_to_currency') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-4">{{translate('Transaction_History')}}</h5>
                            <div class="d-flex flex-column gap-2">
                                @foreach($loyalty_point_list as $key=>$item)
                                <div class="bg-light p-3 p-sm-4 rounded d-flex justify-content-between gap-3">
                                    <div class="">
                                        <h4 class="mb-2">{{ $item['debit'] != 0 ? $item['debit'] : $item['credit'] }}</h4>
                                        <h6 class="text-muted">{{str_replace('_', ' ',$item['transaction_type'])}}</h6>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted mb-1">{{date('d M, Y H:i A',strtotime($item['created_at']))}} </div>
                                        @if($item['debit'] != 0)
                                            <p class="text-danger fs-12">{{translate('Debit')}}</p>
                                        @else
                                            <p class="text-info fs-12">{{translate('Credit')}}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!-- Transaction History Empty -->
                            @if($loyalty_point_list->count()==0)
                            <div class="d-flex flex-column gap-3 align-items-center text-center my-5">
                                <img width="72" src="{{theme_asset('assets/img/media/empty-transaction-history.png')}}" class="dark-support" alt="">
                                <h6 class="text-muted">{{translate('You_don’t_have_any')}} <br> {{translate('transaction_yet')}}</h6>
                            </div>
                            @endif
                            <div class="card-footer bg-transparent border-0 p-0 mt-3">
                                {{$loyalty_point_list->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->

    <!-- Modal -->
    <div class="modal fade" id="convertToCurrency" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header px-sm-5">
                    <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('convert_to_currency')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('loyalty-exchange-currency')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="text-start mb-2">
                            {{translate('your_loyalty_point_will_convert_to_currency_and_transfer_to_your_wallet')}}
                        </div>
                        <div class="text-center">
                            <span class="text-warning">
                                {{translate('minimum_point_for_convert_to_currency_is :')}} {{App\CPU\Helpers::get_business_settings('loyalty_point_minimum_point')}} {{translate('point')}}
                            </span>
                        </div>
                        <div class="text-center mb-2">
                            <span >
                                {{App\CPU\Helpers::get_business_settings('loyalty_point_exchange_rate')}} {{translate('point')}} = {{\App\CPU\Helpers::currency_converter(1)}}
                            </span>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input class="form-control" type="number"  name="point" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  data-bs-dismiss="modal" aria-label="Close"class="btn btn-secondary">{{translate('close')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Instruction Modal -->
    <div class="modal fade" id="instructionModal" tabindex="-1" aria-labelledby="instructionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instructionModalLabel">{{ translate('how_to_use') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>{{translate('Convert_your_loyalty_point_to_wallet_money.')}}</li>
                        <li>{{translate('Minimum')}} {{App\CPU\Helpers::get_business_settings('loyalty_point_minimum_point')}} {{translate('points_required_to_convert')}} <br>{{translate('into_currency')}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
