@extends('theme-views.layouts.app')

@section('title', translate('Terms_&_Conditions').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')

<!-- Main Content -->
<main class="main-content d-flex flex-column gap-3 pb-3">
    <div class="page-title overlay py-5 __opacity-half" data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}">
        <div class="container">
            <h1 class="absolute-white text-center">{{translate('Terms_&_Conditions')}}</h1>
        </div>
    </div>
    <div class="container">
        <div class="card my-4">
            <div class="card-body p-lg-4 text-dark page-paragraph">
                {!!$terms_condition->value!!}
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->

@endsection
