<!-- Review Modal -->
<div class="modal fade" id="contact_sellerModal" tabindex="-1" aria-labelledby="contact_sellerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header px-sm-5 pb-1">
                <h5 class="" id="contact_sellerModalLabel">{{translate('contact_With_Seller')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5">
                <form action="{{route('messages_store')}}" method="post" id="contact_with_seller_form" data-success-message="{{translate('send_successfully')}}">
                    @csrf
                    @if($shop_id != 0)
                        <input value="{{$shop_id}}" name="shop_id" hidden>
                        <input value="{{$seller_id}}" name="seller_id" hidden>
                        <input value="first_message" name="first_message" hidden>
                    @endif

                    <textarea name="message" class="form-control" row="8" required placeholder="{{translate('Type your message')}}"></textarea>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex">
                            <button class="btn btn-primary text-white me-2" {{($shop_id == 0?'disabled':'')}}>{{translate('send')}}</button>
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{translate('close')}}</button>
                        </div>
                        <div>
                            @if($shop_id != 0)
                            <a href="{{route('chat', ['type' => 'seller'])}}" class="btn btn-primary text-white me-2 {{($shop_id == 0?'d-none':'')}}">{{translate('Go_To_Chatbox')}}</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
