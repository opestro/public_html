@extends('layouts.front-end.app')

@section('title', \App\CPU\translate('OTP_verification'))


@section('content')
    <div class="container py-4 py-lg-5 my-4 __inline-8">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <h2 class="h3 mb-4">{{\App\CPU\translate('provide_your_otp_and_proceed')}}?</h2>
                <div class="card py-2 mt-4">
                    <form class="card-body needs-validation" action="{{route('customer.auth.otp-verification')}}"
                          method="post">
                        @csrf
                        <div class="form-group">
                            <div class="resend_otp_custom text-center">
                                <p class="text-primary mb-2 ">{{ translate('Resend_code_within') }}</p>
                                <h6 class="text-primary mb-5 verifyTimer">
                                    <span class="verifyCounter" data-second="{{$time_count}}"></span>s
                                </h6>
                            </div>

                            <label>{{\App\CPU\translate('Enter your OTP')}}</label>
                            <div id="divOuter">
                                <div id="divInner">
                                    <input id="partitioned" class="form-control" name="otp" type="text" maxlength="4" />
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary resend-otp-button" type="button" id="resend_otp">{{ translate('Resend_OTP') }}</button>
                        <button class="btn btn--primary" type="submit">{{\App\CPU\translate('proceed')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var obj = document.getElementById('partitioned');
        obj.addEventListener('keydown', stopCarret);
        obj.addEventListener('keyup', stopCarret);

        function stopCarret() {
            if (obj.value.length > 3){
                setCaretPosition(obj, 3);
            }
        }

        function setCaretPosition(elem, caretPos) {
            if(elem != null) {
                if(elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                }
                else {
                    if(elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    }
                    else
                        elem.focus();
                }
            }
        }
    </script>

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
