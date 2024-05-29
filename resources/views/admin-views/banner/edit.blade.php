@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Banner'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/banner.png')}}" alt="">
                {{\App\CPU\translate('banner_update_form')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.banner.update',[$banner['id']])}}" method="post" enctype="multipart/form-data"
                              class="banner_form">
                            @csrf
                            @method('put')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id">
                                        {{-- <label for="name" class="title-color text-capitalize">{{ \App\CPU\translate('banner_URL')}}</label> --}}
                                        {{-- <input type="text" name="url" class="form-control" value="{{$banner['url']}}" required> --}}
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">{{\App\CPU\translate('banner_type')}}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="banner_type" required id="banner_type_select">
                                            <option value="Main Banner" {{$banner['banner_type']=='Main Banner'?'selected':''}}>Main Banner</option>
                                            <option value="Footer Banner" {{$banner['banner_type']=='Footer Banner'?'selected':''}}>Footer Banner</option>
                                            <option value="Popup Banner" {{$banner['banner_type']=='Popup Banner'?'selected':''}}>Popup Banner</option>
                                            <option value="Main Section Banner" {{$banner['banner_type']=='Main Section Banner'?'selected':''}}>{{ \App\CPU\translate('Main Section Banner')}}</option>
                                            @if(theme_root_path() == 'theme_aster')
                                                <option value="Header Banner" {{$banner['banner_type']=='Header Banner'?'selected':''}}>{{ \App\CPU\translate('Header Banner')}}</option>
                                                <option value="Sidebar Banner" {{$banner['banner_type']=='Sidebar Banner'?'selected':''}}>{{ \App\CPU\translate('Sidebar Banner')}}</option>
                                                <option value="Top Side Banner" {{$banner['banner_type']=='Top Side Banner'?'selected':''}}>{{ \App\CPU\translate('Top Side Banner')}}</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="resource_id" class="title-color text-capitalize">{{\App\CPU\translate('resource_type')}}</label>
                                        <select onchange="display_data(this.value)"
                                                class="js-example-responsive form-control w-100"
                                                name="resource_type" required>
                                            <option value="product" {{$banner['resource_type']=='product'?'selected':''}}>Product</option>
                                            <option value="category" {{$banner['resource_type']=='category'?'selected':''}}>Category</option>
                                            <option value="shop" {{$banner['resource_type']=='shop'?'selected':''}}>Shop</option>
                                            <option value="brand" {{$banner['resource_type']=='brand'?'selected':''}}>Brand</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-0" id="resource-product" style="display: {{$banner['resource_type']=='product'?'block':'none'}}">
                                        <label for="product_id" class="title-color text-capitalize">{{\App\CPU\translate('product')}}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="product_id">
                                            @foreach(\App\Model\Product::active()->get() as $product)
                                                <option value="{{$product['id']}}" {{$banner['resource_id']==$product['id']?'selected':''}}>{{$product['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-0" id="resource-category" style="display: {{$banner['resource_type']=='category'?'block':'none'}}">
                                        <label for="name" class="title-color text-capitalize">{{\App\CPU\translate('category')}}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="category_id">
                                            @foreach(\App\CPU\CategoryManager::parents() as $category)
                                                <option value="{{$category['id']}}" {{$banner['resource_id']==$category['id']?'selected':''}}>{{$category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-0" id="resource-shop" style="display: {{$banner['resource_type']=='shop'?'block':'none'}}">
                                        <label for="shop_id" class="title-color text-capitalize">{{\App\CPU\translate('shop')}}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="shop_id">
                                            @foreach(\App\Model\Shop::active()->get() as $shop)
                                                <option value="{{$shop['id']}}" {{$banner['resource_id']==$shop['id']?'selected':''}}>{{$shop['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-0" id="resource-brand" style="display: {{$banner['resource_type']=='brand'?'block':'none'}}">
                                        <label for="brand_id" class="title-color text-capitalize">{{\App\CPU\translate('brand')}}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="brand_id">
                                            @foreach(\App\Model\Brand::all() as $brand)
                                                <option value="{{$brand['id']}}" {{$banner['resource_id']==$brand['id']?'selected':''}}>{{$brand['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-4 mb-0">
                                        <label for="name" class="title-color text-capitalize">{{ \App\CPU\translate('banner_URL')}}</label>
                                        <input type="url" name="url" class="form-control" id="url" required placeholder="{{ translate('Enter_url') }}" value="{{$banner['url']}}">
                                    </div>

                                    {{-- For Theme Fashion - New input Field - Start --}}
                                    @if(theme_root_path() == 'theme_fashion')
                                    <div class="form-group mt-4 input_field_for_main_banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                        <label for="button_text" class="title-color text-capitalize">{{ translate('Button_Text')}}</label>
                                        <input type="text" name="btn_text" class="form-control" id="button_text" placeholder="{{ translate('Enter_button_text') }}" value="{{$banner['button_text']}}">
                                    </div>
                                    <div class="form-group mt-4 mb-0 input_field_for_main_banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                        <label for="background_color" class="title-color text-capitalize">{{ \App\CPU\translate('background_color')}}</label>
                                        <input type="color" name="background_color" class="form-control" id="background_color" value="{{$banner['background_color']}}">
                                    </div>
                                    @endif
                                    {{-- For Theme Fashion - New input Field - End --}}

                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-end">
                                    <div>
                                        <center>
                                            <img
                                                class="ratio-4:1"
                                                id="mbImageviewer"
                                                src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}"
                                                onerror='this.src="{{asset('public/assets/front-end/img/placeholder.png')}}"'
                                                alt=""/>
                                        </center>
                                        <label for="name" class="mt-3">{{ \App\CPU\translate('Image')}}</label><span
                                            class="ml-1 text-info" id="theme_ratio">( {{\App\CPU\translate('ratio')}} 4:1 )</span>
                                        <br>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="mbimageFileUploader"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label"
                                                    for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                        </div>

                                         {{-- For Theme Fashion - New input Field - Start --}}
                                         @if(theme_root_path() == 'theme_fashion')
                                         <div class="form-group mt-4 input_field_for_main_banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                             <label for="title" class="title-color text-capitalize">{{ translate('Title')}}</label>
                                             <input type="text" name="title" class="form-control" id="title" placeholder="{{ translate('Enter_banner_title') }}" value="{{$banner['title']}}">
                                         </div>
                                         <div class="form-group mb-0 input_field_for_main_banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                             <label for="sub_title" class="title-color text-capitalize">{{ translate('Sub_Title')}}</label>
                                             <input type="text" name="sub_title" class="form-control" id="sub_title" placeholder="{{ translate('Enter_banner_sub_title') }}" value="{{$banner['sub_title']}}">
                                         </div>
                                         @endif
                                         {{-- For Theme Fashion - New input Field - End --}}

                                    </div>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end gap-3">
                                    <button type="reset" class="btn btn-secondary px-4">{{ \App\CPU\translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary px-4">{{ \App\CPU\translate('update')}}</button>
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
        $(document).on('ready', function () {
            theme_wise_ration();
        });

        function theme_wise_ration(){
            let banner_type = $('#banner_type_select').val();
            let theme = '{{ theme_root_path() }}';
            let theme_ratio = {!! json_encode(THEME_RATIO) !!};
            let get_ratio= theme_ratio[theme][banner_type];

            $('#theme_ratio').text(get_ratio);
        }

        $('#banner_type_select').on('change',function(){
            theme_wise_ration();
        });

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

    <!-- New Added JS - Start -->
    <script>
        $('#banner_type_select').on('change',function(){
            let input_value = $(this).val();

            if (input_value == "Main Banner") {
                $('.input_field_for_main_banner').removeClass('d-none');
            } else {
                $('.input_field_for_main_banner').addClass('d-none');
            }
        });
    </script>
    <!-- New Added JS - End -->

@endpush
