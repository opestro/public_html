@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('theme_setup'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/teheme-setup.png')}}" alt="">
                {{\App\CPU\translate('system_setup')}}
            </h2>

            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder">
                {{ translate('How_the_Setting_Works') }}
                <div class="ripple-animation" data-toggle="modal" data-target="#settingModal">
                    <img src="{{asset('public/assets/back-end/img/icons/info.svg')}}" class="svg" alt="">
                </div>
            </div>

            <div class="modal fade" id="settingModal" tabindex="-1" aria-labelledby="settingModal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                            <button
                                type="button"
                                class="btn-close border-0"
                                data-dismiss="modal"
                                aria-label="Close"
                            ><i class="tio-clear"></i></button>
                        </div>
                        <div class="modal-body px-4 px-sm-5 pt-0 text-center">
                            <div class="row g-2 g-sm-3 mt-lg-0">
                                <div class="col-12">
                                    <div class="swiper mySwiper pb-3">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <img src="{{asset('public/assets/back-end/img/slider-1.png')}}"
                                                     loading="lazy"
                                                     alt="" class="dark-support rounded">
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="d-flex flex-column align-items-center mx-w450 mx-auto">
                                                    <img src="{{asset('public/assets/back-end/img/slider-2.png')}}"
                                                         loading="lazy"
                                                         alt="" class="dark-support rounded mb-4">
                                                    <p>
                                                        {{ translate('get_your_zip_file_from_the_purchased_theme_and_upload_it_and_activate_theme_with_your_Codecanyon_username_and_purchase_code') }}
                                                        .
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="d-flex flex-column align-items-center mx-w450 mx-auto">
                                                    <img src="{{asset('public/assets/back-end/img/slider-3.png')}}"
                                                         loading="lazy"
                                                         alt="" class="dark-support rounded mb-4">
                                                    <p>
                                                        {{ translate('now_you’ll_be_successfully_able_to_use_the_theme_for_your_6Valley_website') }}
                                                    </p>
                                                    <p>
                                                        {{ translate('N:B you_can_upload_only_6Valley’s_theme_templates') }}
                                                        .
                                                    </p>
                                                    <button class="btn btn-primary px-10 mt-3"
                                                            data-dismiss="modal">{{ translate('Got_It') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.system-settings-inline-menu')
        <!-- End Inlile Menu -->

        <!-- File Upload Card -->
        <div class="card mb-5">
            <div class="card-body pl-md-10">
                <h4 class="mb-3 text-capitalize d-flex align-items-center">{{\App\CPU\translate('upload_theme')}}</h4>
                <form enctype="multipart/form-data" id="theme_form">
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-5 col-xl-4 col-xxl-3">
                            <!-- Drag & Drop Upload -->
                            <div class="uploadDnD">
                                <div class="form-group inputDnD">
                                    <input type="file" name="theme_upload"
                                           class="form-control-file text--primary font-weight-bold" id="inputFile"
                                           onchange="readUrl(this)" accept=".zip"
                                           data-title="Drag & drop file or Browse file">
                                </div>
                            </div>

                            <div class="mt-5 card px-3 py-2 d--none" id="progress-bar">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="">
                                        <img width="24" src="{{asset('/public/assets/back-end/img/zip.png')}}" alt="">
                                    </div>
                                    <div class="flex-grow-1 text-start">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                            <span id="name_of_file" class="text-truncate fz-12"></span>
                                            <span class="text-muted fz-12" id="progress-label">0%</span>
                                        </div>
                                        <progress id="uploadProgress" class="w-100" value="0" max="100"></progress>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php($condition_one=str_replace('MB','',ini_get('upload_max_filesize'))>=20 && str_replace('MB','',ini_get('upload_max_filesize'))>=20)
                        @php($condition_two=str_replace('MB','',ini_get('post_max_size'))>=20 && str_replace('MB','',ini_get('post_max_size'))>=20)

                        <div class="col-sm-6 col-lg-5 col-xl-4 col-xxl-9">
                            <div class="pl-sm-5">
                                <h5 class="mb-3 d-flex">{{ \App\CPU\translate('instructions') }}</h5>
                                <ul class="pl-3 d-flex flex-column gap-2 instructions-list">
                                    <li>
                                        1. {{ translate('please_make_sure') }}, {{ translate('your_server_php') }}
                                        "upload_max_filesize" {{translate('value_is_grater
                                   _or_equal_to_20MB') }}. {{ translate('current_value_is') }}
                                        - {{ini_get('upload_max_filesize')}}B
                                    </li>
                                    <li>
                                        2. {{ translate('please_make_sure')}}, {{ translate('your_server_php')}}
                                        "post_max_size"
                                        {{translate('value_is_grater_or_equal_to_20MB')}}
                                        . {{translate('current_value_is') }} - {{ini_get('post_max_size')}}B
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if($condition_one && $condition_two)
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'button':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'theme_install()':'call_demo()'}}"
                                        class="btn btn--primary px-4"
                                        id="upload_theme">{{\App\CPU\translate('upload')}}</button>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Theme Items -->
        <div class="row g-1 g-sm-2">
            @foreach($themes as $key => $theme)
                <div class="col-6 col-md-4 col-xxl-4">
                    <div class="card theme-card {{ theme_root_path() == $key ? 'theme-active':'' }}">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ ucfirst(str_replace('_', ' ', $key=='default' ? 'default_theme' : $theme['name'])) }}
                            </h3>

                            <div class="d-flex gap-2 gap-sm-3 align-items-center">
                                @if($key!='default' && theme_root_path() != $key)
                                    <button class="text-danger bg-transparent p-0 border-0" data-toggle="modal"
                                            data-target="#deleteThemeModal_{{ $key }}"><img
                                            src="{{asset('public/assets/back-end/img/icons/delete.svg')}}" class="svg"
                                            alt=""></button>

                                    <!-- Delete Theme Modal -->
                                    <div class="modal fade" id="deleteThemeModal_{{ $key }}" tabindex="-1"
                                         aria-labelledby="deleteThemeModal_{{ $key }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                                                    <button
                                                        type="button"
                                                        class="btn-close border-0"
                                                        data-dismiss="modal"
                                                        aria-label="Close"
                                                    ><i class="tio-clear"></i></button>
                                                </div>
                                                <div class="modal-body px-4 px-sm-5 text-center">
                                                    <div class="mb-3 text-center">
                                                        <img width="75"
                                                             src="{{asset('public/assets/back-end/img/delete.png')}}"
                                                             alt="">
                                                    </div>

                                                    <h3>{{ \App\CPU\translate('are_you_sure_you_want_to_delete_the_theme') }}
                                                        ?</h3>
                                                    <p class="mb-5">{{ \App\CPU\translate('once_you_delete') }}
                                                        , {{ \App\CPU\translate('you_will_lost_the_this_theme') }}</p>
                                                    <div class="d-flex justify-content-center gap-3 mb-3">
                                                        <button type="button" class="fs-16 btn btn-secondary px-sm-5"
                                                                data-dismiss="modal">{{ translate('cancel') }}</button>
                                                        <button type="submit" class="fs-16 btn btn-danger px-sm-5"
                                                                data-dismiss="modal"
                                                                onclick="theme_delete('{{ $key }}')">{{ translate('delete') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(theme_root_path() == $key)
                                    <button class="c1 bg-transparent p-0 border-0"><img
                                            src="{{asset('public/assets/back-end/img/icons/check.svg')}}" class="svg"
                                            alt=""></button>

                                @else
                                    <button class="text-muted bg-transparent p-0 border-0" data-toggle="modal"
                                            data-target="#shiftThemeModal_{{ $key }}"><img
                                            src="{{asset('public/assets/back-end/img/icons/check.svg')}}" class="svg"
                                            alt=""></button>

                                    <div class="modal fade" id="shiftThemeModal_{{ $key }}" tabindex="-1"
                                         aria-labelledby="shiftThemeModalLabel_{{ $key }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                                                    <button
                                                        type="button"
                                                        class="btn-close border-0"
                                                        data-dismiss="modal"
                                                        aria-label="Close"
                                                    ><i class="tio-clear"></i></button>
                                                </div>
                                                <div class="modal-body px-4 px-sm-5 text-center">
                                                    <div class="mb-3 text-center">
                                                        <img width="75"
                                                             src="{{asset('public/assets/back-end/img/shift.png')}}"
                                                             alt="">
                                                    </div>

                                                    <h3>{{ translate('do_you_want_to_shift_in_another_theme') }}</h3>
                                                    <p class="mb-5">{{ translate('if_you_shift_in_another_theme') }}
                                                        , {{ translate('everything_will_be_rearranged') }} <br
                                                            class="d-none d-sm-inline"> {{ translate('according_to_theme') }}
                                                    </p>
                                                    <div class="d-flex justify-content-center gap-3 mb-3">
                                                        <button type="button" class="fs-16 btn btn-secondary px-sm-5"
                                                                data-dismiss="modal">{{ translate('no') }}</button>
                                                        <button type="button" class="fs-16 btn btn--primary px-sm-5"
                                                                data-dismiss="modal"
                                                                onclick="theme_publish('{{ $key }}')">{{ translate('yes') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="p-2 p-sm-3">
                            <div class="aspect-ration-3:2 border border-color-primary-light radius-10">
                                <img class="img-fit radius-10"
                                     onerror='this.src="{{asset('public/assets/front-end/img/placeholder.png')}}"'
                                     src="{{ asset('resources/themes/'.$key.'/public/addon/'.$theme['image']) }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @include('admin-views.business-settings.partials.theme-activate-modal')
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>

    <script>
        function readUrl(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    let imgData = e.target.result;
                    let imgName = input.files[0].name;
                    input.setAttribute("data-title", imgName);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <script>
        function theme_install() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
                },
            });

            var formData = new FormData(document.getElementById('theme_form'));

            $.ajax({
                type: 'POST',
                url: "{{route('admin.business-settings.web-config.theme.install')}}",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    $('#progress-bar').show();

                    // Listen to the upload progress event
                    xhr.upload.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
                            var percentage = Math.round((e.loaded * 100) / e.total);
                            $("#uploadProgress").val(percentage);
                            $("#progress-label").text(percentage + "%");
                        }
                    }, false);

                    return xhr;
                },
                beforeSend: function () {
                    $('#upload_theme').attr('disabled');
                },
                success: function (response) {
                    if (response.status == 'error') {
                        $('#progress-bar').hide();
                        toastr.error(response.message);
                    } else if (response.status == 'success') {
                        toastr.success(response.message);
                        location.reload();
                    }
                },
                complete: function () {
                    $('#upload_theme').removeAttr('disabled');
                },
            });
        }

        function theme_publish(theme) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.business-settings.web-config.theme.publish')}}',
                data: {
                    theme
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.flag === 'inactive') {
                        $('#activateData').empty().html(data.view);
                        $("#activatedThemeModal").addClass('bg-soft-dark').modal("show");
                    } else {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success('{{ translate("updated successfully!") }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            setTimeout(function () {
                                location.reload()
                            }, 2000);
                        }
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function theme_delete(theme) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.business-settings.web-config.theme.delete')}}',
                data: {
                    theme
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.status === 'success') {
                        setTimeout(function () {
                            location.reload()
                        }, 2000);

                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else if (data.status === 'error') {
                        toastr.error(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        var swiper = new Swiper(".mySwiper", {
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
            },
        });
    </script>
@endpush
