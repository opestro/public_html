@extends('layouts.front-end.app')

@section('title', \App\CPU\translate('Verify'))

@section('content')
    <div class="container py-4 py-lg-5 my-4 __inline-7">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 box-shadow">
                    @if ($user_verify == 0)
                    <div class="card-body">
                        <div class="text-center">
                            <h2 class="h4 mb-1">{{\App\CPU\translate('one_step_ahead')}}</h2>
                            <p class="font-size-sm text-muted mb-4">{{\App\CPU\translate('verify_information_to_continue')}}.</p>
                            <div class="resend_otp_custom">
                                <p class="text-primary mb-2 ">{{ translate('Resend_code_within') }}</p>
                                <h6 class="text-primary mb-5 verifyTimer">
                                    <span class="verifyCounter" data-second="{{$get_time}}"></span>s
                                </h6>
                            </div>
                        </div>
                        <form class="needs-validation_" id="sign-up-form" action="{{ route('customer.auth.verify') }}"
                              method="post">
                            @csrf
                            <div class="col-sm-12">
                                @php($email_verify_status = \App\CPU\Helpers::get_business_settings('email_verification'))
                                @php($phone_verify_status = \App\CPU\Helpers::get_business_settings('phone_verification'))
                                <div class="form-group">
                                    @if(\App\CPU\Helpers::get_business_settings('email_verification'))
                                        <label for="reg-phone" class="text-primary">
                                            *
                                            {{\App\CPU\translate('please') }}
                                            {{\App\CPU\translate('provide') }}
                                            {{\App\CPU\translate('verification') }}
                                            {{\App\CPU\translate('token') }}
                                            {{\App\CPU\translate('sent_in_your_email') }}
                                        </label>
                                    @elseif(\App\CPU\Helpers::get_business_settings('phone_verification'))
                                        <label for="reg-phone" class="text-primary">
                                            *
                                            {{\App\CPU\translate('please') }}
                                            {{\App\CPU\translate('provide') }}
                                            {{\App\CPU\translate('OTP') }}
                                            {{\App\CPU\translate('sent_in_your_phone') }}
                                        </label>
                                    @else
                                        <label for="reg-phone" class="text-primary">* {{\App\CPU\translate('verification_code') }} / {{ \App\CPU\translate('OTP')}}</label>
                                    @endif
                                    <input class="form-control" type="text" name="token" required>
                                </div>
                            </div>
                            <input type="hidden" value="{{$user->id}}" name="id">
                            <button class="btn btn-outline-primary resend-otp-button" type="button" id="resend_otp">{{ translate('Resend_OTP') }}</button>
                            <button type="submit" class="btn btn-outline-primary">{{\App\CPU\translate('verify')}}</button>
                        </form>
                    </div>
                    @else
                    <div class=" p-5">
                        <div class="row">
                            <div class="col-md-12">
                                <center>
                                    <i class="fa fa-check-circle __text-100px __color-0f9d58"></i>
                                </center>

                                <span class="font-weight-bold d-block mt-4 __text-17px text-center">{{translate('Hello')}}, {{$user->f_name}}</span>
                                <h5 class="font-black __text-20px text-center my-2">{{translate('Verification_Successfully_Done!')}}!</h5>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                                <a href="{{route('customer.auth.login')}}" class="btn btn-sm btn--primary">
                                    {{translate('Login')}}
                                </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        // Resend OTP
        $('#resend_otp').click(function(){
            $('input.otp-field').val('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: `{{route('customer.auth.resend_otp')}}`,
                method: 'POST',
                dataType: 'json',
                data: {
                    'user_id':{{$user->id}},
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.status == 1) {
                        // Countdown
                        let new_counter = $(".verifyCounter");
                        let new_seconds = data.new_time;
                        function new_tick() {
                            let m = Math.floor(new_seconds / 60);
                            let s = new_seconds % 60;
                            new_seconds--;
                            new_counter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
                            if (new_seconds > 0) {
                                setTimeout(new_tick, 1000);
                                $('.resend-otp-button').attr('disabled', true);
                                $(".resend_otp_custom").slideDown();
                            }
                            else {
                                $('.resend-otp-button').removeAttr('disabled');
                                $(".verifyCounter").html("0:00");
                                $(".resend_otp_custom").slideUp();
                            }
                        }
                        new_tick();

                        toastr.success(`{{translate('OTP_has_been_sent_again.')}}`);
                    } else {
                        toastr.error(`{{translate('please_wait_for_new_code.')}}`);
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
