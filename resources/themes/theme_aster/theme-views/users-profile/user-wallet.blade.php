@extends('theme-views.layouts.app')

@section('title', translate('My_Wallet').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">

                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="row g-0 g-md-3 h-100">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between gap-2">
                                        <h5 class="mb-4 flex-grow-1">{{translate('My_Wallet')}}</h5>
                                        <span class="text-dark d-md-none" data-bs-toggle="modal" data-bs-target="#instructionModal"><i class="bi bi-info-circle"></i></span>
                                    </div>

                                    <div class="wallet-card pb-3 rounded-10 overlay ov-hidden" data-bg-img="{{ theme_asset('assets/img/media/wallet-card.png') }}" style="--bg-color: var(--bs-primary-rgb);">
                                        <div class="card-body d-flex flex-column gap-2 absolute-white">
                                            <img width="34" src="{{theme_asset('assets/img/icons/profile-icon5.png')}}" alt="" class="dark-support">
                                            <h2 class="fs-36 absolute-white">{{\App\CPU\Helpers::currency_converter($total_wallet_balance)}}</h2>
                                            <p>{{translate('Total_Balance')}}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 d-none d-md-block">
                                        <h6 class="mb-3">{{translate('How_to_use')}}</h6>
                                        <ul>
                                            <li>{{translate('Earn_money_to_your_wallet_by_completing_the_offer_&_challenged')}}</li>
                                            <li>{{translate('Convert_your_loyalty_points_into_wallet_money')}}</li>
                                            <li>{{translate('Amin_also_reward_their_top_customers_with_wallet_money')}}</li>
                                            <li>{{translate('Send_your_wallet_money_while_order')}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="mb-4">{{ translate('Transaction_History') }}</h5>
                                    <!-- is Transaction History Empty -->
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($wallet_transactio_list as $key=>$item)
                                        <div class="bg-light p-3 p-sm-4 rounded d-flex justify-content-between gap-3">
                                            <div class="">
                                                <h4 class="mb-2">{{ $item['debit'] != 0 ? \App\CPU\Helpers::currency_converter($item['debit']) : \App\CPU\Helpers::currency_converter($item['credit']) }}</h4>
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
                                    @if($wallet_transactio_list->count()==0)
                                    <div class="d-flex flex-column gap-3 align-items-center text-center my-5">
                                        <img width="72" src="{{theme_asset('assets/img/media/empty-transaction-history.png')}}" class="dark-support" alt="">
                                        <h6 class="text-muted">{{translate('You_don’t_have_any ')}}<br> {{translate('transaction_yet')}}</h6>
                                    </div>
                                    @endif
                                    <div class="card-footer bg-transparent border-0 p-0 mt-3">
                                        {{$wallet_transactio_list->links()}}
                                    </div>
                                    <!-- End Transaction History Empty -->
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <li>{{translate('Earn_money_to_your_wallet_by_completing_the_offer_&_challenged')}}</li>
                            <li>{{translate('Convert_your_loyalty_points_into_wallet_money')}}</li>
                            <li>{{translate('Amin_also_reward_their_top_customers_with_wallet_money')}}</li>
                            <li>{{translate('Send_your_wallet_money_while_order')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection
