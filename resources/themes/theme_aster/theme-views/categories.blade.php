@extends('theme-views.layouts.app')

@section('title',\App\CPU\translate('All Category Page'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Categories of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Categories of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <style>
.image-container {
        width: 100px; /* Adjust this value to your desired image size */
        height: 100px; /* Set the same value as width to make it squared */
        overflow: hidden;
        position: relative;
}
                                                    
.image-square {
        width: 100%;
        height: 100%;
        object-fit: cover;
}

@media screen and (max-width: 991px) {
    .card.filter-toggle-aside {
        display: none;
    }
}

    </style>
@endpush

@section('content')
 
      <section>
            <div class="container">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4">
                                <h3 class="mb-1">{{translate('All_categories')}}</h3>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb fs-12 mb-0">
                                      <li class="breadcrumb-item"><a href="#">{{ translate('home') }}</a></li>
                                      <li class="breadcrumb-item" >{{translate('All_categories')}}</li>
                                    </ol>
                                  </nav>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="flexible-grid lg-down-1 gap-3" style="--width: 16rem">
                    <div class="card filter-toggle-aside">
                        <div class="d-flex d-lg-none pb-0 p-3 justify-content-end">
                            <button class="filter-aside-close border-0 bg-transparent">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <div class="card-body d-flex flex-column gap-4">
                            <!-- Categories -->
                            <div>
                                <h6 class="mb-3">{{translate('Categories')}}</h6>
                                @php($categories=\App\CPU\CategoryManager::parents())
                                <div class="products_aside_categories">
                                    <ul class="common-nav flex-column nav custom-scrollbar flex-nowrap custom_common_nav">
                                        @foreach($categories as $category)
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{$category['name']}}</a>
                                                @if ($category->childes->count() > 0)
                                                <span>
                                                    <i class="bi bi-chevron-right"></i>
                                                </span>
                                                @endif
                                            </div>
                                            <!-- Sub Menu -->
                                            @if ($category->childes->count() > 0)
                                            <ul class="sub_menu">
                                                @foreach($category->childes as $child)
                                                <li>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}">{{$child['name']}}</a>
                                                        @if ($child->childes->count() > 0)
                                                        <span>
                                                            <i class="bi bi-chevron-right"></i>
                                                        </span>
                                                        @endif
                                                    </div>

                                                    @if ($child->childes->count() > 0)
                                                    <ul class="sub_menu">
                                                        @foreach($child->childes as $ch)
                                                        <li>
                                                            <label class="custom-checkbox">
                                                                <a href="{{route('products',['id'=> $ch['id'],'data_from'=>'category','page'=>1])}}">{{$ch['name']}}</a>
                                                            </label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @if ($categories->count() > 10)
                                <div class="d-flex justify-content-center">
                                    <button class="btn-link text-primary btn_products_aside_categories">{{translate('More_Categories')}}...</button>
                                </div>
                                @endif
                            </div>
                    </div>
                    
                    


                </div>
                
             <div id="ajax-products-view">
                       
                        
                            <div class="auto-col gap-3 mobile_two_items ">
                                 @foreach($categories as $key=>$category)
                                                <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="swiper-slide store-product d-flex flex-column gap-3 align-items-center">
                                                    <div class="image-container">
                                                          <img src="{{ asset('storage/app/public/category')}}/{{$category['icon']}}" alt="" class="category-icon" loading="lazy">
                                                      </div>
                                                    <div class="d-flex flex-column align-items-center text-center gap-2 w-100">
                                                        <h4 class="category-name text-truncate text-center" style="font-size: 18px; white-space: pre-line;">{{$category['name']}}</h6>
                                                        
                                                    </div>
                                                </a>
                                    @endforeach
                        
                            </div>
                    
                        
                    
            </div>
        </div>
             
        </section>
    

@endsection

