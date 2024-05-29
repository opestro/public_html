@extends('layouts.back-end.app')

@section('title', translate('features_Section'))

@push('css_or_js')
<style>
    .features_Section_add_input_css {
        background-color: #f1f1f1;
        border-radius: 10px;
    }

    .features_Section_add_input_css span {
        font-size: 5rem;
        color: #bbb;
        transition: .5s ease-in;
    }

    .features_Section_add_input_css:hover span {
        color: #969696;
    }

    .custom_img_upload {
        position: relative;
        width: 100%;
        height: 150px;
        border-radius: 5px;
        overflow: hidden;
        background-color: #cacaca4d;
        display: flex;
        justify-content: center;
    }

    .custom_img_upload span.icon {
        position: absolute;
        right: 5px;
        top: 5px;
        background-color: #07203a34;
        border-radius: 50px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
    }

    .custom_img_upload img {
        object-fit: cover;
    }

    .custom_img_upload_grp {
        width: 100%;
        background: #4444440d;
        border-radius: 5px;
        border: 2px dashed #ffffff29;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        color: #00000063;
    }

    .custom_img_upload_grp label {
        cursor: pointer;
        width: 100%;
        padding: 15px 0;
    }

    .custom_img_upload_grp label span {
        font-size: 18px;
        font-weight: 600;
    }

    .custom_img_upload_grp input {
        display: none;
    }

</style>
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/Pages.png')}}" alt="">
            {{ translate('pages') }}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inlile Menu -->
    @include('admin-views.business-settings.pages-inline-menu')
    <!-- End Inlile Menu -->
    <form action="{{ route('admin.business-settings.features-section.submit') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ translate('features_Section') }} - {{ translate('top') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-3">
                                <label for="title">{{ translate('title') }}</label>
                                <input type="text" class="form-control" name="features_section_top[title]"
                                    placeholder="{{ translate('type_your_title_text') }}"
                                    value="{{ isset($features_section_top) ? json_decode($features_section_top->value)->title : '' }}">
                            </div>
                            <div class="col-sm-12 col-md-6 mb-3">
                                <label for="subtitle">{{ translate('sub_Title') }}</label>
                                <input type="text" class="form-control" name="features_section_top[subtitle]"
                                    placeholder="{{ translate('type_your_subtitle_text') }}"
                                    value="{{ isset($features_section_top) ? json_decode($features_section_top->value)->subtitle : '' }}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="mb-0">{{ translate('features_Section') }} - {{ translate('middle') }}</h5>
                        <span onclick="addThisFeaturesCard_middle()" class="btn btn--primary"><i class="tio-add pr-2"></i>{{ translate('add_New') }}</span>
                    </div>
                    <div class="card-body">

                        <div class="row" id="features_Section_middle_row">
                            @if (isset($features_section_middle) && !empty($features_section_middle) )
                                @forelse (json_decode($features_section_middle->value) as $item)
                                <div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                                    <div class="card">
                                        <div class="card-header justify-content-end">
                                            <div class="cursor-pointer removeThisFeaturesCard_class"><i class="tio-add-to-trash"></i> {{ translate('remove') }}</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_middle[title][]"
                                                    value="{{ $item->title }}" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">{{ translate('sub_Title') }}</label>
                                                <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}">{{ $item->subtitle  }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                                    <div class="card">
                                        <div class="card-header justify-content-end">
                                            <div class="cursor-pointer removeThisFeaturesCard_class"><i class="tio-add-to-trash"></i> {{ translate('remove') }}</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_middle[title][]"
                                                    value="" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">{{ translate('sub Title') }}</label>
                                                <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforelse

                            @endif

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="mb-0">{{ translate('features_Section') }} - {{ translate('bottom') }}</h5>
                        <span onclick="addThisFeaturesCard_bottom()" class="btn btn--primary"><i class="tio-add pr-2"></i>{{ translate('add_New') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row" id="features_Section_bottom_row">

                            @if (isset($features_section_bottom) && !empty($features_section_bottom) )
                                @forelse (json_decode($features_section_bottom->value) as $key => $item)
                                @php($card_index = rand(1111, 9999))
                                <div class="col-sm-12 col-md-3 mb-4">
                                    <div class="card">
                                        <div class="card-header justify-content-end">
                                            <span class="cursor-pointer text-danger remove_icon_box_with_titles" data-title="{{ $item->title }}" data-subtitle="{{ $item->subtitle }}">
                                                <i class="tio-add-to-trash"></i> {{ translate('Delete') }}
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control" disabled value="{{ $item->title }}"
                                                name="icontitle"
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">Sub Title</label>
                                                <textarea class="form-control" disabled
                                                    placeholder="{{ translate('type_your_subtitle_text') }}">{{ $item->subtitle }}</textarea>
                                            </div>

                                            <div class="mb-3 d-flex">
                                                <div class="custom_img_upload">
                                                    <img id="pre_img_header_logo{{ $card_index }}" src="{{asset('storage/app/public/banner')}}/{{$item->icon}}"
                                                        onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                                    <div class="card">
                                        <div class="card-header align-items-center justify-content-between">
                                            <h5 class="m-0">{{ translate('icon_box') }}</h5>
                                            <div class="cursor-pointer removeThisFeaturesCard_class"><i class="tio-add-to-trash"></i> {{ translate('remove') }}</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_bottom[title][]"
                                                    value="" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">Sub Title</label>
                                                <textarea class="form-control" name="features_section_bottom[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                            </div>

                                            <div class="mb-3 d-flex">
                                                <div class="custom_img_upload">
                                                    <span class="icon" onclick="clearSiteIMGInput('img_header_logo')">
                                                        <i class="tio-add-to-trash"></i>
                                                    </span>
                                                    <img id="pre_img_header_logo" src=""
                                                        onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'">
                                                </div>
                                                <div class="custom_img_upload_grp">
                                                        <label for="img_header_logo" class="form-label text-center">
                                                            <span>{{ translate('upload_Icon') }}</span><br>- {{ translate('click') }} -
                                                        </label>

                                                        <input type="file" id="img_header_logo"
                                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                            name="features_section_bottom_icon[]"
                                                            onchange="document.getElementById('pre_img_header_logo').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            @endif

                        </div>

                        <div class="col-12 d-flex justify-content-start mt-4 py-2">
                            <button type="submit" class="btn btn--primary px-5">{{ translate('submit') }}</button>
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </form>
</div>
@endsection

@push('script')
<script>
    function clearSiteIMGInput(id) {
        $('#' + id).val('');
        // $('#pre_' + id).attr('onerror', '');
        $('#pre_' + id).attr('src', '');
    };

    $('.removeThisFeaturesCard_class').on('click', function() {
        $(this).closest('.removeThisFeaturesCard_div').remove();
    });

    $('.removeThisFeaturesIcon_btn').on('click', function() {
        $(this).closest('.featuresIcon_div').remove();
    });

    function addThisFeaturesCard_middle() {
        let index = Math.floor((Math.random() * 100)+1);

        let html = `<div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                        <div class="card">
                            <div class="card-header justify-content-end">
                                <div class="cursor-pointer removeThisFeaturesCard_class"><i class="tio-add-to-trash"></i> {{ translate('remove') }}</div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title">{{ translate('title') }}</label>
                                    <input type="text" class="form-control" required
                                        name="features_section_middle[title][]"
                                        placeholder="{{ translate('type_your_title_text') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="title">{{ translate('sub_Title') }}</label>
                                    <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                        placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>`;

        $('#features_Section_middle_row').append(html);

        $('.removeThisFeaturesCard_class').on('click', function() {
            $(this).closest('.removeThisFeaturesCard_div').remove();
        });
    }

    function addThisFeaturesCard_bottom() {
        let index = Math.floor((Math.random() * 100)+1);

        let html = `<div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                        <div class="card">
                            <div class="card-header align-items-center justify-content-between">
                                <h5 class="m-0">{{ translate('icon_box') }}</h5>
                                <div class="cursor-pointer removeThisFeaturesCard_class"><i class="tio-add-to-trash"></i> {{ translate('remove') }}</div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title">{{ translate('title') }}</label>
                                    <input type="text" class="form-control" required
                                        name="features_section_bottom[title][]"
                                        placeholder="{{ translate('type_your_title_text') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="title">{{ translate('sub_Title') }}</label>
                                    <textarea class="form-control" name="features_section_bottom[subtitle][]" required
                                        placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                </div>

                                <div class="mb-3 d-flex">
                                    <div class="custom_img_upload">
                                        <span class="icon" onclick="clearSiteIMGInput('img_header_logo${index}')">
                                            <i class="tio-add-to-trash"></i>
                                        </span>
                                        <img id="pre_img_header_logo${index}" src=""
                                            onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'">
                                    </div>
                                    <div class="custom_img_upload_grp">
                                            <label for="img_header_logo${index}" class="form-label text-center">
                                                <span>{{ translate('upload_Icon') }}</span><br>- {{ translate('click') }} -
                                            </label>

                                            <input type="file" id="img_header_logo${index}"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                name="features_section_bottom_icon[]"
                                                onchange="document.getElementById('pre_img_header_logo${index}').src = window.URL.createObjectURL(this.files[0])">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>`;

        $('#features_Section_bottom_row').append(html);

        $('.removeThisFeaturesCard_class').on('click', function() {
            $(this).closest('.removeThisFeaturesCard_div').remove();
        });
    }
</script>

<script>
    $('.remove_icon_box_with_titles').on('click',function(){

    $.ajax({
        url: `{{ route('admin.business-settings.features-section.icon-remove') }}`,
        method: 'POST',
        data: {
            _token:$('meta[name="_token"]').attr('content'),
            title:$(this).data('title'),
            subtitle:$(this).data('subtitle'),
        },
        success: function (data) {
            if (data.status =="success") {
                location.reload();
            }
        },
    });
})
</script>
@endpush
