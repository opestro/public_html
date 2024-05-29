
<div class="modal fade" id="reviewModalCustomer{{ $product->id }}" tabindex="-1" aria-labelledby="reviewModalCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header px-sm-5">
                <h1 class="modal-title fs-5" id="reviewModalCustomerLabel">{{translate('Submit_a_review')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('store_customer') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-body px-sm-5">
                    <!-- Rating -->
                    <div class="form-group mb-4">
                        <label for="rating">{{ translate('Rating') }}</label>
                        <select name="rating" id="rating" class="form-select">
                            <option value="1">{{ translate('1') }}</option>
                            <option value="2">{{ translate('2') }}</option>
                            <option value="3">{{ translate('3') }}</option>
                            <option value="4">{{ translate('4') }}</option>
                            <option value="5">{{ translate('5') }}</option>
                        </select>
                    </div>

                    <!-- Comment -->
                    <div class="form-group mb-4">
                        <label for="comment">{{ translate('Comment') }}</label>
                        <input name="product_id" value="{{ $product->id }}" hidden>
                        
                        <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="{{ translate('Leave a comment') }}"></textarea>
                    </div>

                <div class="modal-footer gap-3 pb-4 px-sm-5">
                    <a href="{{ URL::previous() }}" class="btn btn-secondary m-0" data-bs-dismiss="modal">{{ translate('Back') }}</a>
                    <button type="submit" class="btn btn-primary m-0">{{ translate('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
