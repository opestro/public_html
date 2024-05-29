@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Order Complete'))

@push('css_or_js')
    <style>

        .spanTr {
            color: {{$web_config['primary_color']}};
        }

        .amount {
            color: {{$web_config['primary_color']}};
        }

        @media (max-width: 600px) {
            .orderId {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 91px;
            }
        }
        /*  */
    </style>
@endpush

@section('content')
    <div class="container mt-5 mb-5 rtl __inline-53"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="card">
                    @if(auth('customer')->check())
                        <div class=" p-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="font-black __text-20px">{{\App\CPU\translate('your_order_has_been_placed_successfully!')}}
                                        </h5>
                                </div>
                                
                                
                                
                                
                                
                            </div>
                            
                           
                            
                          

                            <div class="row mb-4">
                                <div class="col-12">
                                    <center>
                                        <i class="fa fa-check-circle __text-100px __color-0f9d58"></i>
                                    </center>
                                </div>
                            </div>

                            <span class="font-weight-bold d-block mt-4 __text-17px">{{\App\CPU\translate('Hello')}}, {{auth('customer')->user()->f_name}}</span>
                            <span>{{\App\CPU\translate('You order has been confirmed and will be shipped according to the method you selected!')}}</span>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <a href="{{route('home')}}" class="btn btn--primary">
                                        {{\App\CPU\translate('go_to_shopping')}}
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="{{route('account-oder')}}"
                                       class="btn btn-secondary pull-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                        {{\App\CPU\translate('check_orders')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
