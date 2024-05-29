@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('edit_seller'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid main-card {{Session::get('direction')}}">

    <!-- Page Title -->
    <div class="mb-4">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" class="mb-1" alt="">
            {{\App\CPU\translate('edit_seller')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <form class="user" action="{{route('admin.sellers.updateInfo')}}" method="post" enctype="multipart/form-data">
    @csrf
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="status" value="approved">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                                <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                                {{\App\CPU\translate('seller_information')}}
                            </h5>
                           <div class="row align-items-center">
                                <div class="col-lg-6 mb-4 mb-lg-0">
                                    <input type="hidden" name="id" value="{{$seller->id}}">
                                    <div class="form-group">
                                        <label for="duration" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('duration')}}</label>
                                        <select class="form-control" id="duration" name="duration">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{$i}}" {{$seller->shop->duration == $i ? 'selected' : ''}}>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="abonnement_type" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('abonnement_type')}}</label>
                                        <select class="form-control" id="abonnement_type" name="abonnement_type">
                                            <option value="BASIC 1200DA" {{$seller->shop->address == 'BASIC 1200DA' ? 'selected' : ''}}>BASIC 1200DA</option>
                                            <option value="PRIME 2200DA" {{$seller->shop->address == 'PRIME 2200DA' ? 'selected' : ''}}>PRIME 2200DA</option>
                                            <option value="PRO 3300DA" {{$seller->shop->address == 'PRO 3300DA' ? 'selected' : ''}}>PRO 3300DA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div class="d-flex align-items-center justify-content-end gap-10">
                            <input type="hidden" name="from_submit" value="admin">
                            <button type="submit" class="btn btn--primary btn-user" id="apply">{{\App\CPU\translate('submit')}}</button>
                        </div>
                    </div>
                    
                </div>

       
                   

               
           
        
    </form>
</div>
@endsection


