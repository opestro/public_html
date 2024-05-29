<!-- Register Modal -->
<div class="modal fade"
     id="registerModal"
     tabindex="-1"
     aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body px-4 px-lg-5">
                <div class="mb-4 text-center">
                        <img
                            width="200"
                            src="https://nichen.net/storage/app/company/Logo.png"
                            alt=""
                            class="theme-support"
                        />
                </div>
                <div class="mb-4">
                    <h2 class="mb-2">{{ translate('sign_up') }}</h2>
                    <p class="text-muted">
                        {{ translate('login_to_your_account') }}. {{ translate('Don’t_have_account') }}?
                        <span
                            class="text-primary fw-bold"
                            data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            {{ translate('login') }}
                        </span>
                    </p>
                </div>

                <form action="{{ route('customer.auth.sign-up') }}" method="POST" id="customer_form" enctype="multipart/form-data">
                    @csrf
                    <div class="custom-scrollbar">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label for="f_name"> {{ translate('First_Name') }}</label>
                                    <input
                                        type="text"
                                        id="f_name"
                                        name="f_name"
                                        class="form-control"
                                        placeholder="Ex: Jhone"
                                        value="{{old('f_name')}}"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label for="l_name">{{ translate('Last_Name') }}</label>
                                    <input
                                        type="text"
                                        id="l_name"
                                        name="l_name"
                                        value="{{old('l_name')}}"
                                        class="form-control"
                                        placeholder="Ex: Doe"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label for="r_email">{{ translate('email') }}</label>
                                    <input
                                        type="text"
                                        id="r_email"
                                        value="{{old('email')}}"
                                        name="email"
                                        class="form-control"
                                        placeholder="{{ translate('enter_email_or_phone_number') }}"
                                        autocomplete="off"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label for="phone">{{ translate('phone') }}</label>
                                    <input
                                        type="tel" pattern="0\d{9}" maxlength="10" minlength="10" size="10" 
                                        id="phone"
                                        value="{{old('phone')}}"
                                        name="phone"
                                        class="form-control"
                                        placeholder="{{ translate('enter_phone_number') }}"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <label for="password">{{ translate('password') }}</label>
                                    <div class="input-inner-end-ele">
                                        <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            class="form-control"
                                            placeholder="{{ translate('minimum_8_characters_long') }}"
                                            autocomplete="off"
                                            required
                                        />
                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <label for="confirm_password">{{ translate('Confirm_Password') }}</label>
                                    <div class="input-inner-end-ele">
                                        <input
                                            type="password"
                                            id="confirm_password"
                                            class="form-control"
                                            name="con_password"
                                            placeholder="{{ translate('minimum_8_characters_long') }}"
                                            autocomplete="off"
                                            required
                                        />
                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($web_config['recaptcha']['status'] == 1)
                            <div class="d-flex justify-content-center">
                                <div id="recaptcha_element_customer_regi" class="w-100 mt-2" data-type="image"></div>
                            </div>
                        @else
                            <div class="d-flex gap-3 justify-content-center py-2 mt-4 mb-3">
                                <div class="">
                                    <input type="text" class="form-control border __h-40" name="default_recaptcha_value_customer_regi" value=""
                                        placeholder="{{\App\CPU\translate('Enter captcha value')}}" autocomplete="off">
                                </div>
                                <div class="input-icons rounded bg-white">
                                    <a onclick="re_captcha_customer_regi();" class="d-flex align-items-center align-items-center">
                                        <img src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_regi') }}" class="input-field rounded __h-40" id="customer_regi_recaptcha_id">
                                        <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center mt-4">
                            <label for="agree" class="d-flex gap-1 align-items-center mb-0">
                                <input type="checkbox" id="inputCheckd" required/>
                                {{translate('i_agree_with_the')}} <a href="{{route('terms')}}" class="text-info">{{ translate('Terms_&_Conditions') }}</a>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4 mb-3">
                        <button type="submit" id="sign-up" class="btn btn-primary px-5" disabled>{{ translate('Sign_Up') }}</button>
                    </div>
                </form>
                
                @if(Session::get('device') !== 'mobile')
                    @if($web_config['social_login_text'])
                        <p  id="socialLoginText" class="text-center text-muted">{{ translate('or_continue_with') }}</p>
                    @endif
                    <div id="socialLoginImages" class="d-flex justify-content-center gap-3 align-items-center flex-wrap pb-3" >
                        @foreach ($web_config['socials_login'] as $socialLoginService)
                            @if (isset($socialLoginService) && $socialLoginService['status']==true)
                                <a href="{{route('customer.auth.service-login', $socialLoginService['login_medium'])}}">
                                    <img
                                        width="35"
                                        src="{{ theme_asset('assets/img/svg/'.$socialLoginService['login_medium'].'.svg') }}"
                                        alt=""
                                        class="dark-support"/>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallbackCustomerRegi&render=explicit" async defer></script>

    <script>
        $('#inputCheckd').change(function () {
            if ($(this).is(':checked')) {
                $('#sign-up').removeAttr('disabled');
            } else {
                $('#sign-up').attr('disabled', 'disabled');
            }

        });

        @if($web_config['recaptcha']['status'] == '1')
            var onloadCallbackCustomerRegi = function () {
                let reg_id = grecaptcha.render('recaptcha_element_customer_regi', {
                    'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
                });
                $('#recaptcha_element_customer_regi').attr('data-reg-id', reg_id);
            };

            function recaptcha_f(){
                let response = grecaptcha.getResponse($('#recaptcha_element_customer_regi').attr('data-reg-id'));
                if (response.length === 0) {
                    return false;
                }else{
                    return true;
                }
            }
        @else
            function re_captcha_customer_regi() {
                $url = "{{ URL('/customer/auth/code/captcha') }}";
                $url = $url + "/" + Math.random()+'?captcha_session_id=default_recaptcha_id_customer_regi';
                document.getElementById('customer_regi_recaptcha_id').src = $url;
            }
        @endif

        $('#customer_form').submit(function(event) {
            event.preventDefault();
            let formData = $(this).serialize()
            let recaptcha = true;

            @if($web_config['recaptcha']['status'] == '1')
                recaptcha = recaptcha_f();
            @endif

            if(recaptcha === true) {
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    beforeSend: function () {
                        $("#loading").addClass("d-grid");
                    },
                    success: function (data) {
                        // return false;
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i], {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                            @if($web_config['recaptcha']['status'] != '1')
                                re_captcha_customer_regi()
                            @endif
                        } else {
                            toastr.success(
                                '{{translate('Customeer_Added_Successfully')}}!', {
                                    CloseButton: true,
                                    ProgressBar: true
                            });
                            if (data.redirect_url !== '') {
                                window.location.href = data.redirect_url;
                            } else {
                                $('#registerModal').modal('hide');
                                $('#loginModal').modal('show');
                            }
                        }
                    },
                    complete: function () {
                        $("#loading").removeClass("d-grid");
                    },
                });
            } else{
                toastr.error("{{translate('Please check the recaptcha')}}");
            }
        });
    </script>
@endpush
