@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('OTP_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')

    <!-- End Inlile Menu -->
        <form action="{{ route('admin.business-settings.otp-setup-update') }}" method="post"
              enctype="multipart/form-data" id="update-settings">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="maximum_otp_hit">{{translate('maximum OTP hit')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('The maximum OTP hit is a measure of how many times a specific one-time password has been generated and used within a time.') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" value="{{$maximum_otp_hit}}"
                                       name="maximum_otp_hit" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="otp_resend_time">{{translate('OTP resend time')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('If the user fails to get the OTP within a certain time, user can request a resend') }}">
                                    </i>
                                    <span class="text-danger">( {{ translate('in_seconds') }} )</span>
                                </label>
                                <input type="number" min="0" step="0.01" value="{{$otp_resend_time}}"
                                       name="otp_resend_time" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="temporary_block_time">{{translate('temporary_block_time')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('Temporary OTP block time refers to a security measure implemented by systems to restrict access to OTP service for a specified period of time for wrong OTP submission.') }}">
                                    </i>
                                    <span class="text-danger">( {{ translate('in_seconds') }} )</span>
                                </label>
                                <input type="number" min="0" value="{{$temporary_block_time}}" step="0.01"
                                       name="temporary_block_time" class="form-control" placeholder="" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="maximum_otp_hit">{{translate('maximum Login hit')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('The maximum login hit is a measure of how many times a user can submit password within a time.') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" value="{{$maximum_login_hit}}"
                                       name="maximum_login_hit" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="temporary_block_time">{{translate('temporary_login_block_time')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('Temporary login block time refers to a security measure implemented by systems to restrict access for a specified period of time for wrong Password submission') }}">
                                    </i>
                                    <span class="text-danger">( {{ translate('in_seconds') }} )</span>
                                </label>
                                <input type="number" min="0" step="0.01" value="{{$temporary_login_block_time}}"
                                       name="temporary_login_block_time" class="form-control" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <button type="reset" class="btn btn-secondary px-4">{{translate('reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-4">
                            {{translate('save')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
