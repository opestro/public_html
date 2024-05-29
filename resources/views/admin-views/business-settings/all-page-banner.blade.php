@extends('layouts.back-end.app')

@section('title', translate('All_Pages_Banner '))

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
        <div class="row pb-4 d--none" id="main-banner"
             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{ translate('banner_form')}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.all-pages-banner-store') }}" method="post" enctype="multipart/form-data"
                              class="banner_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id">
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">{{ translate('banner_type') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="type" required>
                                            <option value="banner_product_list_page">{{ translate('Product_List_Page')}}</option>
                                            <option value="banner_terms_conditions">{{ translate('Terms_and_Conditions')}}</option>
                                            <option value="banner_privacy_policy">{{ translate('Privacy_Policy')}}</option>
                                            <option value="banner_refund_policy">{{ translate('Refund_Policy')}}</option>
                                            <option value="banner_return_policy">{{ translate('Return_Policy')}}</option>
                                            <option value="banner_cancellation_policy">{{ translate('Cancellation_Policy')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ translate('Image')}}</label>
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
                                                src="{{asset('public/assets/front-end/img/placeholder.png')}}"
                                                alt="banner image"/>
                                        </center>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4" type="reset">{{ translate('reset')}}</button>
                                    <button id="add" type="submit"
                                            class="btn btn--primary px-4">{{ translate('save')}}</button>
                                    <button id="update"
                                       class="btn btn--primary d--none text-white">{{ translate('update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="banner-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ translate('banner_table')}}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12">{{ $page_banners->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-md-8 col-lg-6">
                                <div
                                    class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                    <!-- Search -->
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search"
                                                   class="form-control"
                                                   placeholder="{{ translate('Search_by_Banner_Type')}}"
                                                   aria-label="Search orders" value="{{ $search }}">
                                            <button type="submit" class="btn btn--primary">
                                                {{ translate('Search')}}
                                            </button>
                                        </div>
                                    </form>
                                    <!-- End Search -->

                                    <div id="banner-btn">
                                        <button id="main-banner-add" class="btn btn--primary text-nowrap">
                                            <i class="tio-add"></i>
                                            {{ translate('add_banner')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                               style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th class="pl-xl-5">{{translate('SL')}}</th>
                                <th>{{ translate('image')}}</th>
                                <th>{{ translate('banner_type')}}</th>
                                <th>{{ translate('published')}}</th>
                                <th class="text-center">{{ translate('action')}}</th>
                            </tr>
                            </thead>
                            @foreach($page_banners as $key=>$banner)
                                <tbody>
                                <tr id="data-{{$banner->id}}">
                                    <td class="pl-xl-5">{{$page_banners->firstItem()+$key}}</td>
                                    <td>
                                        <img class="ratio-4:1" width="80"
                                             onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'"
                                             src="{{asset('storage/app/public/banner')}}/{{json_decode($banner['value'])->image}}">
                                    </td>
                                    <td>{{translate(ucwords(str_replace('_',' ',$banner->type)))}}</td>
                                    <td>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input status"
                                                   id="{{$banner->id}}" {{ json_decode($banner['value'])->status == 1?"checked":"" }}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                               title="{{ translate('Edit')}}"
                                               href="{{route('admin.business-settings.all-pages-banner-edit',[$banner['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                               title="{{ translate('Delete')}}"
                                               id="{{$banner['id']}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$page_banners->links()}}
                        </div>
                    </div>

                    @if(count($page_banners)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160"
                                 src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                 alt="Image Description">
                            <p class="mb-0">{{ translate('No_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
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
    <script>
        $('#main-banner-add').on('click', function () {
            $('#main-banner').show();
        });

        $('.cancel').on('click', function () {
            $('.banner_form').attr('action', "{{route('admin.business-settings.all-pages-banner-store')}}");
            $('#main-banner').hide();
        });

        $(document).on('change', '.status', function () {
            let id = $(this).attr("id");
            let status = $(this).prop("checked") === true ? 1 : 0;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.all-pages-banner-status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    if (data == 1) {
                        toastr.success('{{translate('Banner_published_successfully')}}');
                    } else {
                        toastr.success('{{translate('Banner_unpublished_successfully')}}');
                    }
                    location.reload();
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{translate('Are_you_sure_delete_this_banner')}}?",
                text: "{{translate('You_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate('Yes')}}, {{translate('delete_it')}}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.business-settings.all-pages-banner-delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function (response) {
                            console.log(response)
                            toastr.success('{{translate('Banner_deleted_successfully')}}');
                            $('#data-' + id).hide();
                        }
                    });
                }
            })
        });
    </script>
    <!-- Page level plugins -->
@endpush
