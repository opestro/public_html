<!-- Login Modal -->


<div
    class="modal fade"
    id="loginModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body px-4 px-sm-5">
                <div class="mb-4 text-center">
                    
                        <img
                            width="200"
                            src="https://nichen.net/storage/app/company/Logo.png"
                            alt=""
                            class="theme-support"
                        />
                   
                
                    
                </div>
                
                
                <div class="mb-4">
                    <h2 class="mb-2">{{ translate('login') }}</h2>
                    <p class="text-muted">
                        {{ translate('login_to_your_account') }}. {{ translate('Don’t_have_account') }}?
                        <span
                            class="text-primary fw-bold"
                            data-bs-toggle="modal"
                            data-bs-target="#registerModal">
                            {{translate('Sign_Up')}}
                        </span>
                    </p>
                </div>

                <form action="{{route('customer.auth.login')}}" method="post" id="customer_login_modal" autocomplete="off">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="email">{{ translate('email') }} / {{ translate('phone') }}</label>
                        <input
                            name="user_id" id="si-email"
                            class="form-control" value="{{old('user_id')}}"
                            placeholder="{{translate('Enter_email_or_phone_number')}}" required
                        />
                    </div>

                    <div class="mb-4">
                        <label for="password">{{ translate('password') }}</label>
                        <div class="input-inner-end-ele">
                            <input
                                name="password" type="password" id="si-password"
                                class="form-control"
                                placeholder="{{ translate('Ex:_6+_character') }}"
                                required
                            />
                            <i class="bi bi-eye-slash-fill togglePassword"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-3 align-items-center">
                        <label
                            for="remember_me"
                            class="d-flex gap-1 align-items-center mb-0">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>
                            {{ translate('remember_me') }}
                        </label>

                        <a href="{{route('customer.auth.recover-password')}}">{{ translate('Forgot_Password') }} ?</a>
                    </div>

                    @if($web_config['recaptcha']['status'] == 1)
                        <div class="d-flex justify-content-center mb-3">
                            <div id="recaptcha_element_customer_login" class="w-100 mt-4" data-type="image"></div>
                        </div>
                    @else
                      <!--  <div class="d-flex justify-content-center align-items-center gap-3 py-2 mt-4 mb-3">
                            <div>
                                <input type="text" class="form-control border __h-40" name="default_recaptcha_id_customer_login" value=""
                                       placeholder="{{\App\CPU\translate('Enter captcha value')}}" autocomplete="off">
                            </div>
                            <div class="input-icons rounded bg-white">
                                <a onclick="re_captcha_customer_login();" class="d-flex align-items-center align-items-center">
                                    <img src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_login') }}" class="input-field rounded __h-40" id="customer_login_recaptcha_id">
                                    <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                </a>
                            </div>
                        </div> !-->
                    @endif
                    <div class="d-flex justify-content-center mb-3">
                        <button type="submit" class="fs-16 btn btn-primary px-5">{{ translate('login') }}</button>
                    </div>
                </form>

                @if(Session::get('device')!== 'mobile')
                    @if($web_config['social_login_text'])
                        <p  id="socialLoginText" class="text-center text-muted">{{ translate('or_continue_with') }}</p>
                    @endif
    
                    <div  id="socialLoginImages" class="d-flex justify-content-center gap-3 align-items-center flex-wrap pb-3">
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

    
    {{-- recaptcha scripts start --}}
    @if($web_config['recaptcha']['status'] == 1)
        <script type="text/javascript">
            var onloadCallbackCustomerLogin = function () {
                let login_id = grecaptcha.render('recaptcha_element_customer_login', {
                    'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
                });
                $('#recaptcha_element_customer_login').attr('data-login-id', login_id);
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallbackCustomerLogin&render=explicit" async
                defer></script>

    @else
        <script type="text/javascript">
            function re_captcha_customer_login() {
                $url = "{{ URL('/customer/auth/code/captcha') }}";
                $url = $url + "/" + Math.random()+'?captcha_session_id=default_recaptcha_id_customer_login';
                document.getElementById('customer_login_recaptcha_id').src = $url;
                console.log('url: '+ $url);
            }
        </script>
    @endif
    {{-- recaptcha scripts end --}}

<script src="https://www.gstatic.com/firebasejs/9.14.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.14.0/firebase-messaging-compat.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyD8eBYoyxMH8k_f7Njw8w3HegmLiMFeah4",
        authDomain: "nichen-397921.firebaseapp.com",
        projectId: "nichen-397921",
        storageBucket: "nichen-397921.appspot.com",
        messagingSenderId: "135987346899",
        appId: "1:135987346899:web:f8a63d35a564feafb08326",
        measurementId: "G-PY9WV67R97"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    


</script>

    <script>
        $("#customer_login_modal").submit(function (e) {
            e.preventDefault();
            var customer_recaptcha = true;

            @if($web_config['recaptcha']['status'] == 1)
                var response_customer_login = grecaptcha.getResponse($('#recaptcha_element_customer_login').attr('data-login-id'));

                if (response_customer_login.length === 0) {
                    e.preventDefault();
                    toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
                    customer_recaptcha = false;
                }
            @endif

            if(customer_recaptcha === true) {
                let form = $(this);
                messaging.getToken().then((currentToken) => {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'fcm_token';
                    input.value = currentToken;
                    document.getElementById('customer_login_modal').appendChild(input);
                })
                .catch(function(err) {
                    console.log('User did not allow notifications.');
                })
                .finally(function() {
                    // Move the AJAX request here
                    $.ajax({
                        type: 'POST',
                        url:`{{route('customer.auth.login')}}`,
                        data: form.serialize(),
                        success: function (data) {
                            if (data.status === 'success') {
                                toastr.success(`{{translate('Login_successful')}}`);
                                location.reload();
                            } else if (data.status === 'error') {
                                data.redirect_url !== '' ? window.location.href = data.redirect_url : toastr.error(data.message);
                            }
                        }
                    });
                });
            }
        });
    </script>


@endpush

