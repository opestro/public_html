@extends('theme-views.layouts.app')

@section('title', translate('My_Support_Tickets').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column gap-3 p-2 p-sm-4">
                            <div class="d-flex left gap-2 justify-content-between">
                                <div class="media gap-3"></div>
                                <button class="btn btn-primary rounded-pill px-2 py-0 px-sm-4 py-sm-2" data-bs-toggle="modal" data-bs-target="#reviewModal">{{translate('create_support_tickets')}}</button>
                            </div>
                            @foreach($supportTickets as $key=>$supportTicket)
                            <div class="bg-light rounded-10">
                                <div class="border-bottom support-ticket-row border-grey p-3">
                                    <div class="media gap-2 gap-sm-3">
                                        <div class="avatar">
                                            <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                 src="{{asset('storage/app/public/profile')}}/{{\App\CPU\customer_info()->image}}"
                                                 loading="lazy" class="img-fit dark-support" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column gap-1">
                                                <div class="media align-items-start justify-content-between">
                                                    <div class="media-body">
                                                        <a href="{{route('support-ticket.index',$supportTicket['id'])}}">
                                                            <h6 class="">{{ \App\CPU\customer_info()->f_name }}&nbsp{{ \App\CPU\customer_info()->l_name }}</h6>
                                                        </a>
                                                        <div class="fs-12 text-muted mb-1">{{ \App\CPU\customer_info()->email }}</div>
                                                    </div>
                                                    @if($supportTicket->status != 'close')
                                                        <a href="{{route('support-ticket.close',[$supportTicket['id']])}}" class="btn btn-outline-danger fw-semibold text-nowrap">{{ translate('Close_ticket') }}</a>
                                                    @endif
                                                </div>

                                                <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-3">
                                                    <span
                                                    @if($supportTicket->priority == 'Urgent')
                                                        class="badge rounded-pill bg-danger"
                                                    @elseif($supportTicket->priority == 'High')
                                                        class="badge rounded-pill bg-warning"
                                                    @elseif($supportTicket->priority == 'Medium')
                                                        class="badge rounded-pill bg-info"
                                                    @else
                                                        class="badge rounded-pill bg-success"
                                                        @endif
                                                    >
                                                        {{ $supportTicket->priority }}</span>
                                                    <span class="{{$supportTicket->status ==  'open' ? 'badge bg-info' : 'badge bg-danger'}} rounded-pill">{{ $supportTicket->status }}</span>
                                                    <span class="badge bg-white text-dark">{{ $supportTicket->type }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- @if($supportTicket->status != 'close')
                                    <a href="{{route('support-ticket.close',[$supportTicket['id']])}}" class="btn btn-outline-danger fw-semibold">{{ translate('Close_ticket') }}</a>
                                    @endif -->
                                </div>

                                <div class="d-flex flex-wrap justify-content-between gap-2 p-3">
                                    <h6 class="text-truncate " style="--width: 60ch">{{ $supportTicket->subject }}</h6>
                                    <div class="fs-12">{{date('d M, Y H:i A',strtotime($supportTicket->created_at))}}</div>
                                </div>
                            </div>
                            @endforeach
                            @if($supportTickets->count()==0)
                                <h5 class="text-center">{{translate('not_found_anything')}}</h5>
                            @endif

                            <div class="border-0">
                                {{$supportTickets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header px-sm-5">
                    <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('submit_new_ticket')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="p-3 px-sm-5">
                    <span>{{translate('you_will_get_response')}}.</span>
                </div>
                <div class="modal-body px-sm-5">
                    <form action="{{route('ticket-submit')}}" id="open-ticket" method="post">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="rating">{{ translate('Subject') }}</label>
                            <input type="text" class="form-control" id="ticket-subject" name="ticket_subject" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 mb-4">
                                <label for="rating">{{ translate('Type') }}</label>
                                <select id="ticket-type" name="ticket_type" class="form-select" required>
                                    <option value="Website problem">{{translate('Website')}} {{translate('problem')}}</option>
                                    <option value="Partner request">{{translate('partner_request')}}</option>
                                    <option value="Complaint">{{translate('Complaint')}}</option>
                                    <option value="Info inquiry">{{translate('Info')}} {{translate('inquiry')}} </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="rating">{{ translate('Priority') }}</label>
                                <select id="ticket-priority" name="ticket_priority" class="form-select" required>
                                    <option value>{{translate('choose_priority')}}</option>
                                    <option value="Urgent">{{translate('Urgent')}}</option>
                                    <option value="High">{{translate('High')}}</option>
                                    <option value="Medium">{{translate('Medium')}}</option>
                                    <option value="Low">{{translate('Low')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="comment">{{translate('describe_your_issue')}}</label>
                            <textarea class="form-control" rows="6" id="ticket-description" name="ticket_description" placeholder="{{translate('Leave_your_issue')}}"></textarea>
                        </div>
                        <div class="modal-footer gap-3 pb-4 px-sm-5">
                            <button type="button" class="btn btn-secondary m-0" data-bs-dismiss="modal">{{translate('Back')}}</button>
                            <button type="submit" class="btn btn-primary m-0">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

