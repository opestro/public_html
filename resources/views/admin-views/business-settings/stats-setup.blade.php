@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Stats_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')

    <!-- End Inlile Menu -->
       <form action="{{ route('admin.business-settings.statistics-setup-update') }}" method="post" enctype="multipart/form-data" id="update-stats">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="orders">{{ translate('Orders') }}</label>
                                <input type="number" min="0" value="{{ $orders }}" name="orders" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="stores">{{ translate('Stores') }}</label>
                                <input type="number" min="0" value="{{ $stores }}" name="stores" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="viewers">{{ translate('Viewers') }}</label>
                                <input type="number" min="0" value="{{ $viewers }}" name="viewers" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="returns">{{ translate('Returns') }}</label>
                                <input type="number" min="0" value="{{ $returns }}" name="returns" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="customers">{{ translate('Customers') }}</label>
                                <input type="number" min="0" value="{{ $customers }}" name="customers" class="form-control" placeholder="" required>
                            </div>
                        </div>
                    </div>
        
                    <div class="d-flex gap-3 justify-content-end">
                        <button type="submit" class="btn btn--primary px-4">
                            {{ translate('save') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection
