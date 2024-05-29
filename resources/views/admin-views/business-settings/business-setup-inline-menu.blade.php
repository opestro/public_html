<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/web-config') ?'active':'' }}"><a href="{{route('admin.business-settings.web-config.index')}}">{{\App\CPU\translate('general')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/web-config/app-settings') ?'active':'' }}"><a href="{{route('admin.business-settings.web-config.app-settings')}}">{{\App\CPU\translate('App_Settings')}}</a></li>
        <li class="{{ Request::is('admin/product-settings/inhouse-shop') ?'active':'' }}"><a href="{{ route('admin.product-settings.inhouse-shop') }}">{{\App\CPU\translate('In-House_Shop')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/seller-settings') ?'active':'' }}"><a href="{{route('admin.business-settings.seller-settings.index')}}">{{\App\CPU\translate('Seller')}}</a></li>
        <li class="{{ Request::is('admin/customer/customer-settings') ?'active':'' }}"><a href="{{route('admin.customer.customer-settings')}}">{{\App\CPU\translate('Customer')}}</a></li>
        <li class="{{ Request::is('admin/refund-section/refund-index') ?'active':'' }}"><a href="{{route('admin.refund-section.refund-index')}}">{{\App\CPU\translate('refund')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/shipping-method/setting') ?'active':'' }}"><a href="{{route('admin.business-settings.shipping-method.setting')}}">{{\App\CPU\translate('Shipping_Method')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/order-settings/index') ?'active':'' }}"><a href="{{route('admin.business-settings.order-settings.index')}}">{{\App\CPU\translate('Order')}}</a></li>
        <li class="{{ Request::is('admin/product-settings') ?'active':'' }}"><a href="{{ route('admin.product-settings.index') }}">{{\App\CPU\translate('Product')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/delivery-restriction') ? 'active':'' }}"><a href="{{ route('admin.business-settings.delivery-restriction.index') }}">{{\App\CPU\translate('Delivery_Restriction')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/cookie-settings') ? 'active':'' }}"><a href="{{ route('admin.business-settings.cookie-settings') }}">{{\App\CPU\translate('Cookie_Settings')}}</a></li>
        @if(theme_root_path() == 'theme_fashion')
        <li class="{{ Request::is('admin/business-settings/all-pages-banner') ? 'active':'' }}"><a href="{{ route('admin.business-settings.all-pages-banner') }}">{{translate('All_Pages_Banner')}}</a></li>
        @endif
        <li class="{{ Request::is('admin/business-settings/otp-setup') ? 'active':'' }}"><a href="{{ route('admin.business-settings.otp-setup') }}">{{translate('OTP_and_Login_Setup')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/statistics-setup') ? 'active':'' }}"><a href="{{ route('admin.business-settings.statistics-setup') }}">{{translate('Statistics_Setup')}}</a></li>
    </ul>
</div>
