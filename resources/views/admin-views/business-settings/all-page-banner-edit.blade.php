@extends('layouts.back-end.app')

@section('title', translate('Edit - All_Pages_Banner '))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('All_Pages_Banner')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')
        <!-- End Inlile Menu -->

        <!-- Content Row -->
        <div class="row pb-4" id="main-banner"
             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0 text-capitalize">{{ translate('banner_form')}}</h5>
                        </div>
                        <div>
                            <a class="btn btn--primary text-white" href="{{ route('admin.business-settings.all-pages-banner') }}"><i class="tio-chevron-left"></i> {{ translate('Back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.all-pages-banner-update') }}" method="post" enctype="multipart/form-data"
                              class="banner_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id" value="{{ $banner->id }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">{{ translate('banner_type') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="type" required>
                                            <option value="banner_product_list_page" {{ $banner->type == "banner_product_list_page"?"selected":"" }}>{{ translate('Product_List_Page')}}</option>
                                            <option value="banner_terms_conditions" {{ $banner->type == "banner_terms_conditions"?"selected":"" }}>{{ translate('Terms_and_Conditions')}}</option>
                                            <option value="banner_privacy_policy" {{ $banner->type == "banner_privacy_policy"?"selected":"" }}>{{ translate('Privacy_Policy')}}</option>
                                            <option value="banner_refund_policy" {{ $banner->type == "banner_refund_policy"?"selected":"" }}>{{ translate('Refund_Policy')}}</option>
                                            <option value="banner_return_policy" {{ $banner->type == "banner_return_policy"?"selected":"" }}>{{ translate('Return_Policy')}}</option>
                                            <option value="banner_cancellation_policy" {{ $banner->type == "banner_cancellation_policy"?"selected":"" }}>{{ translate('Cancellation_Policy')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ translate('image')}}</label>
                                        <span class="text-info">( {{ translate('ratio')}} 4:1 )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="mbimageFileUploader"
                                                class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label title-color"
                                                for="mbimageFileUploader">{{ translate('choose')}} {{ translate('file')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-end">
                                    <div>
                                        <center class="mb-30 mx-auto">
                                            <img
                                                class="ratio-6:1"
                                                id="mbImageviewer"
                                                src="{{asset('storage/app/public/banner')}}/{{json_decode($banner['value'])->image}}"
                                                onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'"
                                                alt="banner image"/>
                                        </center>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4" type="reset">{{ translate('reset')}}</button>
                                    <button id="update" type="submit" class="btn btn--primary text-white">{{ translate('update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            // dir: "rtl",
            width: 'resolve'
        });

        function display_data(data) {

            $('#resource-product').hide()
            $('#resource-brand').hide()
            $('#resource-category').hide()
            $('#resource-shop').hide()

            if (data === 'product') {
                $('#resource-product').show()
            } else if (data === 'brand') {
                $('#resource-brand').show()
            } else if (data === 'category') {
                $('#resource-category').show()
            } else if (data === 'shop') {
                $('#resource-shop').show()
            }
        }
    </script>
    <script>
        function mbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function () {
            mbimagereadURL(this);
        });
    </script>
    <!-- Page level plugins -->
@endpush
