@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('OTP_verification').' | '.$web_config['name']->value.''.translate(' Ecommerce'))

@section('content')
<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 py-3 mb-30">
    <div class="card mb-5">
        <div class="card-body py-5 px-lg-5">
            <div class="row align-items-center pb-5">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="text-center mb-2">{{ translate('OTP_Verification') }}</h2>
                    <p class="text-center mb-5 text-muted">{{ translate('Please_Verify_that_it’s_you') }}.</p>
                    <div class="d-flex justify-content-center">
                        <img width="283" class="dark-support" src="{{theme_asset('assets/img/otp-found.png')}}"
                             alt="">
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <img width="50" class="dark-support" src="{{theme_asset('assets/img/otp-lock.png')}}"
                             alt="">
                    </div>
                    <p class="text-muted mx-w mx-auto" style="--width: 27.5rem">{{ translate('An_OTP_(One_Time_Password)_has_been_sent_to') }} {{ request('identity') }}.
                        {{ translate('Please_enter_the_OTP_in_the_field_below_to_verify_your_phone') }}.</p>

                    <div class="resend_otp_custom">
                        <p class="text-primary mb-2 ">{{ translate('Resend_code_within') }}</p>
                        <h6 class="text-primary mb-5 verifyTimer">
                            <span class="verifyCounter" data-second="{{$time_count}}"></span>s
                        </h6>
                    </div>

                    <form action="{{ route('customer.auth.otp-verification') }}" class="otp-form" method="POST"
                          id="customer_verify">
                        @csrf
                        <div class="d-flex gap-2 gap-sm-3 align-items-end justify-content-center">
                            <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                   autocomplete="off">
                            <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                   autocomplete="off">
                            <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                   autocomplete="off">
                            <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                   autocomplete="off">
                        </div>

                        <!-- Store OTP Value -->
                        <input class="otp-value" type="hidden" name="otp">
                        <input class="identity" type="hidden" name="identity" value="{{ request('identity') }}">
                        <div class="d-flex justify-content-center gap-3 mt-5">
                            <button class="btn btn-outline-primary resend-otp-button" type="button" id="resend_otp">{{ translate('Resend_OTP') }}</button>
                            <button class="btn btn-primary px-sm-5" type="submit" disabled>{{ translate('verify') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->
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
                url: `{{route('customer.auth.resend-otp-reset-password')}}`,
                method: 'POST',
                dataType: 'json',
                data: {
                    'identity':{{ request('identity') }},
                },
                beforeSend: function () {
                    $("#loading").addClass("d-grid");
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

                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                complete: function () {
                    $("#loading").removeClass("d-grid");
                },
            });
        });
    </script>
@endpush
