<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\SocialMedia;
use App\Model\Stat;
use Brian2694\Toastr\Facades\Toastr;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\RSA\Formats\Keys\JWK;
use Carbon\Carbon;

class BusinessSettingsController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.general-settings');
    }

    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();
        return view('admin-views.business-settings.about-us', [
            'about_us' => $about_us,
        ]);

    }

    public function about_usUpdate(Request $data)
    {
        $validatedData = $data->validate([
            'about_us' => 'required',
        ]);
        BusinessSetting::where('type', 'about_us')->update(['value' => $data->about_us]);
        Toastr::success('About Us updated successfully!');
        return back();
    }

    public function currency_symbol_position($side)
    {
        $currency_symbol_position = BusinessSetting::where('type', 'currency_symbol_position')->first();
        if (isset($currency_symbol_position) == false) {
            DB::table('business_settings')->insert([
                'type' => 'currency_symbol_position',
                'value' => $side,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('business_settings')->where(['type' => 'currency_symbol_position'])->update([
                'type' => 'currency_symbol_position',
                'value' => $side,
                'updated_at' => now(),
            ]);
        }
        return response()->json(['message' => 'Symbol position is ' . $side]);
    }

    public function business_mode_settings($mode)
    {
        $business_mode = BusinessSetting::where('type', 'business_mode')->first();
        if (isset($business_mode) == false) {
            DB::table('business_settings')->insert([
                'type' => 'business_mode',
                'value' => $mode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('business_settings')->where(['type' => 'business_mode'])->update([
                'type' => 'business_mode',
                'value' => $mode,
                'updated_at' => now(),
            ]);
        }
        return response()->json(['message' => 'Business Mode is changed to ' . $mode. ' vendor']);
    }
    // Social Media
    public function social_media()
    {
        // $about_us = BusinessSetting::where('type', 'about_us')->first();
        return view('admin-views.business-settings.social-media');
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = SocialMedia::where('status', 1)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }

    public function social_media_store(Request $request)
    {
        $check = SocialMedia::where('name', $request->name)->first();
        if ($check != null) {
            return response()->json([
                'error' => 1,
            ]);
        }
        if ($request->name == 'google-plus') {
            $icon = 'fa fa-google-plus-square';
        }
        if ($request->name == 'facebook') {
            $icon = 'fa fa-facebook';
        }
        if ($request->name == 'twitter') {
            $icon = 'fa fa-twitter';
        }
        if ($request->name == 'pinterest') {
            $icon = 'fa fa-pinterest';
        }
        if ($request->name == 'instagram') {
            $icon = 'fa fa-instagram';
        }
        if ($request->name == 'linkedin') {
            $icon = 'fa fa-linkedin';
        }
        $social_media = new SocialMedia;
        $social_media->name = $request->name;
        $social_media->link = $request->link;
        $social_media->icon = $icon;
        $social_media->save();
        return response()->json([
            'success' => 1,
        ]);
    }

    public function social_media_edit(Request $request)
    {
        $data = SocialMedia::where('id', $request->id)->first();
        return response()->json($data);
    }

    public function social_media_update(Request $request)
    {
        $social_media = SocialMedia::find($request->id);
        $social_media->name = $request->name;
        $social_media->link = $request->link;
        $social_media->save();
        return response()->json();
    }

    public function social_media_delete(Request $request)
    {
        $br = SocialMedia::find($request->id);
        $br->delete();
        return response()->json();
    }

    public function social_media_status_update(Request $request)
    {
        SocialMedia::where(['id' => $request['id']])->update([
            'active_status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function page($page)
    {
        $pages = array(
            'refund-policy',
            'return-policy',
            'cancellation-policy',
        );

        if(in_array($page, $pages)){
            $data = BusinessSetting::where('type', $page)->first();
            return view('admin-views.business-settings.page', compact('page', 'data'));
        }

        Toastr::error('invalid_page');
        return redirect()->back();
    }

    public function page_update(Request $request, $page)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $pages = array(
            'refund-policy',
            'return-policy',
            'cancellation-policy',
        );

        if(in_array($page, $pages)){
            BusinessSetting::where('type', $page)->update([
                'value' => json_encode([
                    'status' => is_null($request->status) ? 0 : 1,
                    'content' => $request->value
                ])
            ]);
            Toastr::success('Updated successfully!');
        }else{
            Toastr::error('invalid_page');
        }
        return redirect()->back();
    }

    public function terms_condition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();
        return view('admin-views.business-settings.terms-condition', compact('terms_condition'));
    }

    public function updateTermsCondition(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'terms_condition')->update(['value' => $data->value]);
        Toastr::success('Terms and Condition Updated successfully!');
        return redirect()->back();
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();
        return view('admin-views.business-settings.privacy-policy', compact('privacy_policy'));
    }

    public function privacy_policy_update(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'privacy_policy')->update(['value' => $data->value]);
        Toastr::success('Privacy policy Updated successfully!');
        return redirect()->back();
    }

    public function companyInfo()
    {
        $company_name = BusinessSetting::where('type', 'company_name')->first();
        $company_email = BusinessSetting::where('type', 'company_email')->first();
        $company_phone = BusinessSetting::where('type', 'company_phone')->first();
        return view('admin-views.business-settings.website-info', [
            'company_name' => $company_name,
            'company_email' => $company_email,
            'company_phone' => $company_phone,
        ]);
    }

    public function productSettings()
    {
        $company_name = BusinessSetting::where('type', 'company_name')->first();
        $company_email = BusinessSetting::where('type', 'company_email')->first();
        $company_phone = BusinessSetting::where('type', 'company_phone')->first();
        $digital_product = \App\Model\BusinessSetting::where('type','digital_product')->first()->value;
        $brand = \App\Model\BusinessSetting::where('type','product_brand')->first()->value;

        return view('admin-views.business-settings.product-settings', compact('company_name','company_email','company_phone','digital_product','brand'));
    }

    public function updateInfo(Request $request)
    {
        if ($request['email_verification'] == 1) {
            $request['phone_verification'] = 0;
        } elseif ($request['phone_verification'] == 1) {
            $request['email_verification'] = 0;
        }

        //comapy shop banner
        $imgBanner = BusinessSetting::where(['type' => 'shop_banner'])->first();
        if ($request->has('shop_banner')) {
            $imgBanner = ImageManager::update('shop/', $imgBanner, 'png', $request->file('shop_banner'));
            DB::table('business_settings')->updateOrInsert(['type' => 'shop_banner'], [
                'value' => $imgBanner
            ]);
        }
        // comapny name
        DB::table('business_settings')->updateOrInsert(['type' => 'company_name'], [
            'value' => $request['company_name']
        ]);
        // company email
        DB::table('business_settings')->updateOrInsert(['type' => 'company_email'], [
            'value' => $request['company_email']
        ]);
        // company Phone
        DB::table('business_settings')->updateOrInsert(['type' => 'company_phone'], [
            'value' => $request['company_phone']
        ]);
        //company copy right text
        DB::table('business_settings')->updateOrInsert(['type' => 'company_copyright_text'], [
            'value' => $request['company_copyright_text']
        ]);
        //company time zone
        DB::table('business_settings')->updateOrInsert(['type' => 'timezone'], [
            'value' => $request['timezone']
        ]);
        //country
        DB::table('business_settings')->updateOrInsert(['type' => 'country_code'], [
            'value' => $request['country']
        ]);
        //phone verification
        DB::table('business_settings')->updateOrInsert(['type' => 'phone_verification'], [
            'value' => $request['phone_verification']
        ]);
        //email verification
        DB::table('business_settings')->updateOrInsert(['type' => 'email_verification'], [
            'value' => $request['email_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_verification'], [
            'value' => $request['order_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'forgot_password_verification'], [
            'value' => $request['forgot_password_verification']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'decimal_point_settings'], [
            'value' => $request['decimal_point_settings']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'shop_address'], [
            'value' => $request['shop_address']
        ]);


        //web logo
        $webLogo = BusinessSetting::where(['type' => 'company_web_logo'])->first();
        if ($request->has('company_web_logo')) {
            $webLogo = ImageManager::update('company/', $webLogo, 'png', $request->file('company_web_logo'));
            BusinessSetting::where(['type' => 'company_web_logo'])->update([
                'value' => $webLogo,
            ]);
        }

        //mobile logo
        $mobileLogo = BusinessSetting::where(['type' => 'company_mobile_logo'])->first();
        if ($request->has('company_mobile_logo')) {
            $mobileLogo = ImageManager::update('company/', $mobileLogo, 'png', $request->file('company_mobile_logo'));
            BusinessSetting::where(['type' => 'company_mobile_logo'])->update([
                'value' => $mobileLogo,
            ]);
        }
        //web footer logo
        $webFooterLogo = BusinessSetting::where(['type' => 'company_footer_logo'])->first();
        if ($request->has('company_footer_logo')) {
            $webFooterLogo = ImageManager::update('company/', $webFooterLogo, 'png', $request->file('company_footer_logo'));
            BusinessSetting::where(['type' => 'company_footer_logo'])->update([
                'value' => $webFooterLogo,
            ]);
        }
        //fav icon
        $favIcon = BusinessSetting::where(['type' => 'company_fav_icon'])->first();
        if ($request->has('company_fav_icon')) {
            $favIcon = ImageManager::update('company/', $favIcon, 'png', $request->file('company_fav_icon'));
            BusinessSetting::where(['type' => 'company_fav_icon'])->update([
                'value' => $favIcon,
            ]);
        }

        //loader gif
        $loader_gif = BusinessSetting::where(['type' => 'loader_gif'])->first();
        if ($request->has('loader_gif')) {
            $loader_gif = ImageManager::update('company/', $loader_gif, 'png', $request->file('loader_gif'));
            BusinessSetting::updateOrInsert(['type' => 'loader_gif'], [
                'value' => $loader_gif,
            ]);
        }
        // web color setup
        $colors = BusinessSetting::where('type', 'colors')->first();
        if (isset($colors)) {
            BusinessSetting::where('type', 'colors')->update([
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                        'primary_light' => $request['primary_light'] ?? '#CFDFFB',
                    ]),
            ]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'colors',
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                        'primary_light' => $request['primary_light'] ?? '#CFDFFB',
                    ]),
            ]);
        }

        DB::table('business_settings')->updateOrInsert(['type' => 'default_location'], [
            'value' => json_encode(
                [   'lat' => $request['latitude'],
                    'lng' => $request['longitude'],
                ]),
        ]);

        //pagination
        $request->validate([
            'pagination_limit' => 'numeric',
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'pagination_limit'], [
            'value' => $request['pagination_limit'],
        ]);

        Toastr::success('Updated successfully');
        return back();
    }

    public function announcement()
    {
        $announcement=\App\CPU\Helpers::get_business_settings('announcement');
        return view('admin-views.business-settings.website-announcement', compact('announcement'));
    }

    public function updateAnnouncement(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'announcement'], [
            'value' => json_encode(
                [   'status' => $request['announcement_status'],
                    'color' => $request['announcement_color'],
                    'text_color' => $request['text_color'],
                    'announcement' => $request['announcement'],
                ]),
        ]);

        Toastr::success('Announcement Updated successfully!');
        return back();
    }

    public function updateCompany(Request $data)
    {
        $validatedData = $data->validate([
            'company_name' => 'required',
        ]);
        BusinessSetting::where('type', 'company_name')->update(['value' => $data->company_name]);
        Toastr::success('Company Updated successfully!');
        return redirect()->back();
    }

    public function updateCompanyEmail(Request $data)
    {
        $validatedData = $data->validate([
            'company_email' => 'required',
        ]);
        BusinessSetting::where('type', 'company_email')->update(['value' => $data->company_email]);
        Toastr::success('Company Email Updated successfully!');
        return redirect()->back();
    }

    public function updateCompanyCopyRight(Request $data)
    {
        $validatedData = $data->validate([
            'company_copyright_text' => 'required',
        ]);
        BusinessSetting::where('type', 'company_copyright_text')->update(['value' => $data->company_copyright_text]);
        Toastr::success('Company Copy Right Updated successfully!');
        return redirect()->back();
    }

    public function shop_banner(Request $request)
    {
        $img = BusinessSetting::where(['type' => 'shop_banner'])->first();
        if (isset($img)) {
            $img = ImageManager::update('shop/', $img, 'png', $request->file('image'));
            BusinessSetting::where(['type' => 'shop_banner'])->update([
                'value' => $img,
            ]);
        } else {
            $img = ImageManager::upload('shop/', 'png', $request->file('image'));
            DB::table('business_settings')->insert([
                'type' => 'shop_banner',
                'value' => $img,
            ]);
        }
        return back();
    }

    public function app_settings()
    {
        return view('admin-views.business-settings.apps-settings');
    }

    public function update(Request $request, $name)
    {

        if ($name == 'download_app_apple_stroe') {
            $download_app_store = BusinessSetting::where('type', 'download_app_apple_stroe')->first();
            if (isset($download_app_store) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'download_app_apple_stroe',
                    'value' => json_encode([
                        'status' => 1,
                        'link' => '',

                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'download_app_apple_stroe'])->update([
                    'type' => 'download_app_apple_stroe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'link' => $request['link'],

                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'download_app_google_stroe') {
            $download_app_store = BusinessSetting::where('type', 'download_app_google_stroe')->first();
            if (isset($download_app_store) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'download_app_google_stroe',
                    'value' => json_encode([
                        'status' => 1,
                        'link' => '',

                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'download_app_google_stroe'])->update([
                    'type' => 'download_app_google_stroe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'link' => $request['link'],

                    ]),
                    'updated_at' => now(),
                ]);
            }
        }
        Toastr::success('App Store Updated successfully');

        return back();
    }

    public function updateCompanyPhone(Request $data)
    {
        $validatedData = $data->validate([
            'company_phone' => 'required',
        ]);
        BusinessSetting::where('type', 'company_phone')->update(['value' => $data->company_phone]);
        Toastr::success('Company Phone Updated successfully!');
        return redirect()->back();
    }

    public function uploadWebLogo(Request $data)
    {
        $img = BusinessSetting::where(['type' => 'company_web_logo'])->pluck('value')[0];
        if ($data->image) {
            $img = ImageManager::update('company/', $img, 'png', $data->file('image'));
        }

        BusinessSetting::where(['type' => 'company_web_logo'])->update([
            'value' => $img,
        ]);
        return back();
    }

    public function uploadFooterLog(Request $data)
    {
        $img = BusinessSetting::where(['type' => 'company_footer_logo'])->pluck('value')[0];
        if ($data->image) {
            $img = ImageManager::update('company/', $img, 'png', $data->file('image'));
        }

        BusinessSetting::where(['type' => 'company_footer_logo'])->update([
            'value' => $img,
        ]);
        Toastr::success('Footer Logo updated successfully!');
        return back();

    }

    public function uploadFavIcon(Request $data)
    {
        $img = BusinessSetting::where(['type' => 'company_fav_icon'])->pluck('value')[0];

        if ($data->image) {
            $img = ImageManager::update('company/', $img, 'png', $data->file('image'));
        }

        BusinessSetting::where(['type' => 'company_fav_icon'])->update([
            'value' => $img,
        ]);
        Toastr::success('Fav Icon updated successfully!');
        return back();

    }

    public function uploadMobileLogo(Request $data)
    {
        $img = BusinessSetting::where(['type' => 'company_mobile_logo'])->pluck('value')[0];
        if ($data->image) {
            $img = ImageManager::update('company/', $img, 'png', $data->file('image'));
        }
        BusinessSetting::where(['type' => 'company_mobile_logo'])->update([
            'value' => $img,
        ]);
        return back();
    }

    public function update_colors(Request $request)
    {
        $colors = BusinessSetting::where('type', 'colors')->first();
        if (isset($colors)) {
            BusinessSetting::where('type', 'colors')->update([
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                        'primary_light' => $request['primary_light'] ?? '#CFDFFB',
                    ]),
            ]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'colors',
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                        'primary_light' => $request['primary_light'] ?? '#CFDFFB',
                    ]),
            ]);
        }
        Toastr::success('Color  updated!');
        return back();
    }

    public function fcm_index()
    {
        return view('admin-views.business-settings.fcm-index');
    }

    public function update_fcm(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'fcm_project_id'], [
            'value' => $request['fcm_project_id'],
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'push_notification_key'], [
            'value' => $request['push_notification_key'],
        ]);

        Toastr::success('Settings updated!');
        return back();
    }

    public function update_fcm_messages(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'order_pending_message'], [
            'value' => json_encode([
                'status' => $request['pending_status'],
                'message' => $request['pending_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_confirmation_msg'], [
            'value' => json_encode([
                'status' => $request['confirm_status'],
                'message' => $request['confirm_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_processing_message'], [
            'value' => json_encode([
                'status' => $request['processing_status'],
                'message' => $request['processing_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'out_for_delivery_message'], [
            'value' => json_encode([
                'status' => $request['out_for_delivery_status'],
                'message' => $request['out_for_delivery_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivered_status'],
                'message' => $request['delivered_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_returned_message'], [
            'value' => json_encode([
                'status' => $request['returned_status'],
                'message' => $request['returned_message'],
            ]),
        ]);


        DB::table('business_settings')->updateOrInsert(['type' => 'order_failed_message'], [
            'value' => json_encode([
                'status' => $request['failed_status'],
                'message' => $request['failed_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_assign_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_assign_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_assign_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_start_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_start_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_start_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_delivered_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_expected_delivery_date_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_expected_delivery_date_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_expected_delivery_date_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_canceled'], [
            'value' => json_encode([
                'status' => $request['order_canceled_status'] == 1 ? 1 : 0,
                'message' => $request['order_canceled_message'],
            ]),
        ]);

        Toastr::success('Message updated!');
        return back();
    }

    public function seller_settings()
    {
        $sales_commission = BusinessSetting::where('type', 'sales_commission')->first();
        if (!isset($sales_commission)) {
            DB::table('business_settings')->insert(['type' => 'sales_commission', 'value' => 0]);
        }

        $seller_registration = BusinessSetting::where('type', 'seller_registration')->first();
        if (!isset($seller_registration)) {
            DB::table('business_settings')->insert(['type' => 'seller_registration', 'value' => 1]);
        }

        return view('admin-views.business-settings.seller-settings');
    }

    public function sales_commission(Request $data)
    {
        $validatedData = $data->validate([
            'commission' => 'required|min:0',
        ]);
        $sales_commission = BusinessSetting::where('type', 'sales_commission')->first();

        if (isset($sales_commission)) {
            BusinessSetting::where('type', 'sales_commission')->update(['value' => $data->commission]);
        } else {
            DB::table('business_settings')->insert(['type' => 'sales_commission', 'value' => $data->commission]);
        }

        Toastr::success('Sales commission Updated successfully!');
        return redirect()->back();
    }

    public function seller_registration(Request $data)
    {
        $seller_registration = BusinessSetting::where('type', 'seller_registration')->first();
        if (isset($seller_registration)) {
            BusinessSetting::where(['type' => 'seller_registration'])->update(['value' => $data->seller_registration]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'seller_registration',
                'value' => $data->seller_registration,
                'updated_at' => now()
            ]);
        }

        Toastr::success('Seller registration Updated successfully!');
        return redirect()->back();
    }
    public function seller_pos_settings(Request $request)
    {
        $seller_pos = BusinessSetting::where('type', 'seller_pos')->first();
        if (isset($seller_pos)) {
            BusinessSetting::where(['type' => 'seller_pos'])->update(['value' => $request->seller_pos]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'seller_pos',
                'value' => $request->seller_pos,
                'updated_at' => now()
            ]);
        }

        Toastr::success('Seller pos permission Updated successfully!');
        return redirect()->back();
    }

    public function product_approval(Request $request)
    {

        DB::table('business_settings')->updateOrInsert(['type' => 'new_product_approval'], [
            'value' => $request->new_product_approval == 'on'?1:0
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'product_wise_shipping_cost_approval'], [
            'value' => $request->product_wise_shipping_cost_approval == 'on'?1:0
        ]);
        Toastr::success(\App\CPU\translate('admin_approval_for_products_updated_successfully!'));
        return redirect()->back();
    }

    public function update_language(Request $request)
    {
        $languages = $request['language'];
        if (in_array('en', $languages)) {
            unset($languages[array_search('en', $languages)]);
        }
        array_unshift($languages, 'en');

        DB::table('business_settings')->where(['type' => 'pnc_language'])->update([
            'value' => json_encode($languages),
        ]);
        Toastr::success('Language  updated!');
        return back();
    }

    public function viewSocialLogin()
    {
        return view('admin-views.business-settings.social-login.view');
    }

    public function updateSocialLogin($service, Request $request)
    {
        $socialLogin = BusinessSetting::where('type', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('type', 'social_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success($service . ' credentials  updated!');
        return redirect()->back();

    }

    public function view_social_media_chat()
    {
        return view('admin-views.business-settings.social-media-chat.view');
    }

    public function update_social_media_chat(Request $request, $service)
    {
        if($service == 'messenger'){
            DB::table('business_settings')->updateOrInsert(['type' => 'messenger'], [
                'type' => 'messenger',
                'value' => json_encode([
                    'status' => $request['status'],
                    'script' => $request['script']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }elseif($service == 'whatsapp'){
            DB::table('business_settings')->updateOrInsert(['type' => 'whatsapp'], [
                'type' => 'whatsapp',
                'value' => json_encode([
                    'status' => $request['status'],
                    'phone' => $request['phone']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            Toastr::warning($service . ' information update fail!');
            return redirect()->back();

        }

        Toastr::success($service . ' information  update successfully!');
        return redirect()->back();

    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }
    public function recaptcha_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'recaptcha'], [
            'type' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        Toastr::success('Updated Successfully');
        return back();
    }
    public function map_api()
    {
        return view('admin-views.business-settings.map-api.index');
    }

    public function map_api_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'map_api_key'], [
            'value' => $request['map_api_key']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'map_api_key_server'], [
            'value' => $request['map_api_key_server']
        ]);

        Toastr::success(\App\CPU\translate('config_data_updated'));
        return back();
    }

    public function analytics_index()
    {
        return view('admin-views.business-settings.analytics.index');
    }
    public function analytics_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'pixel_analytics'], [
            'value' => $request['pixel_analytics']
        ]);

        Toastr::success(\App\CPU\translate('config_data_updated'));
        return back();
    }
    public function google_tag_analytics_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'google_tag_manager_id'], [
            'value' => $request['google_tag_manager_id']
        ]);

        Toastr::success(\App\CPU\translate('google_tag_manager_id_updated'));
        return back();
    }

    // stock limit
    public function stock_limit_warning(Request $request){
        DB::table('business_settings')->updateOrInsert(['type' => 'stock_limit'], [
            'value' => $request['stock_limit']
        ]);

        Toastr::success('Updated successfully');
        return back();
    }

    public function updateDigitalProduct(Request $request){
        $digital_product = BusinessSetting::where('type', 'digital_product')->first();
        if (isset($digital_product)) {
            BusinessSetting::where(['type' => 'digital_product'])->update(['value' => $request->digital_product]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'digital_product',
                'value' => $request->digital_product,
                'updated_at' => now()
            ]);
        }

        Toastr::success(\App\CPU\translate('digital_product_updated'));
        return back();
    }

    public function updateProductBrand(Request $request){
        $product_brand = BusinessSetting::where('type', 'product_brand')->first();
        if (isset($product_brand)) {
            BusinessSetting::where(['type' => 'product_brand'])->update(['value' => $request->product_brand]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'product_brand',
                'value' => $request->product_brand,
                'updated_at' => now()
            ]);
        }

        Toastr::success(\App\CPU\translate('product_brand_updated'));
        return back();
    }

    public function countryRestrictionStatusChange(Request $request){

        $delivery_country_restriction_status = BusinessSetting::where('type', 'delivery_country_restriction')->first();

        if (isset($delivery_country_restriction_status)) {
            BusinessSetting::where(['type' => 'delivery_country_restriction'])->update(['value' => $request->status]);
        } else {
            BusinessSetting::insert([
                'type' => 'delivery_country_restriction',
                'value' => $request->status,
                'updated_at' => now()
            ]);
        }
        return [
            'message' =>\App\CPU\translate('delivery_country_restriction_status_changed_successfully'),
            'status' => true
        ];
    }

    public function zipcodeRestrictionStatusChange(Request $request){

        $zip_code_area_restriction_status = BusinessSetting::where('type', 'delivery_zip_code_area_restriction')->first();

        if (isset($zip_code_area_restriction_status)) {
            BusinessSetting::where(['type' => 'delivery_zip_code_area_restriction'])->update(['value' => $request->status]);
        } else {
            BusinessSetting::insert([
                'type' => 'delivery_zip_code_area_restriction',
                'value' => $request->status,
                'updated_at' => now()
            ]);
        }
        return [
            'message' => \App\CPU\translate('delivery_zip_code_restriction_status_changed_successfully'),
            'status' => true,
        ];
    }

    public function cookie_settings(Request $request){
        $data['cookie_setting'] = Helpers::get_business_settings('cookie_setting');

        return view('admin-views.business-settings.cookie-settings', compact('data'));
    }

    public function cookie_setting_update(Request $request){

        $cookie_setting = BusinessSetting::where('type', 'cookie_setting')->first();
        if (isset($cookie_setting)) {
            BusinessSetting::where(['type' => 'cookie_setting'])->update([
                'value' => array(
                    'status'=>$request->status,
                    'cookie_text'=>$request->cookie_text,
                ),
            ]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'cookie_setting',
                'value' => json_encode([
                    'status'=>$request->status,
                    'cookie_text'=>$request->cookie_text,
                ]),
                'updated_at' => now()
            ]);
        }

        Toastr::success('cookie settings updated successfully!');
        return redirect()->back();
    }

    // All Pages Banner - New
    public function all_pages_banner(Request $request)
    {
        $query_param = [];
        $search = $request['search'];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $banners = BusinessSetting::whereIN('type',['banner_product_list_page','banner_privacy_policy','banner_terms_conditions','banner_refund_policy',        'banner_return_policy','banner_cancellation_policy'])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('type', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $banners = BusinessSetting::whereIN('type',['banner_product_list_page','banner_privacy_policy','banner_terms_conditions','banner_refund_policy','banner_return_policy','banner_cancellation_policy'])->get();
        }
        $page_banners = $banners->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.business-settings.all-page-banner', compact('page_banners', 'search'));
    }

    public function all_pages_banner_store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'image' => 'required',
        ]);

        $image = ImageManager::upload('banner/', 'png', $request->file('image'));

        BusinessSetting::insert([
            'type' => $request->type,
            'value' => json_encode([
                'status'=>0,
                'image'=>$image,
            ]),
            'created_at' => now()
        ]);

        Toastr::success('Banner added successfully!');
        return redirect()->back();
    }

    public function all_pages_banner_edit($id)
    {
        $banner = BusinessSetting::where('id', $id)->first();
        return view('admin-views.business-settings.all-page-banner-edit', compact('banner'));
    }

    public function all_pages_banner_update(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $banner = BusinessSetting::find($request->id);
        if($banner){
            ImageManager::delete("/banner/" . json_decode($banner['value'])->image);
            $image = ImageManager::upload('banner/', 'png', $request->file('image'));
            BusinessSetting::where('id', $request->id)->update([
                'type' => $request->type,
                'value' => json_encode([
                    'status'=>0,
                    'image'=>$image,
                ]),
            ]);

            Toastr::success('Banner update successfully!');
        }

        return redirect()->back();
    }


    public function all_pages_banner_status(Request $request)
    {
        $banner = BusinessSetting::find($request->id);
        if($request->status == 1)
        {
            $others_banners = BusinessSetting::where('id', "!=",$request->id)->where('type',$banner->type)->get();
            foreach ($others_banners as $q) {
                BusinessSetting::where(['id'=>$q->id,'type'=>$q->type])->update([
                    'value' => json_encode([
                        'status'=>0,
                        'image'=>json_decode($q['value'])->image,
                    ]),
                ]);
            }
        }

        BusinessSetting::where('id', $request->id)->update([
            'value' => json_encode([
                'status'=>$request->status,
                'image'=>json_decode($banner['value'])->image,
            ]),
        ]);

        $data = $request->status;
        return response()->json($data);
    }

    public function all_pages_banner_delete(Request $request)
    {
        $banner = BusinessSetting::find($request->id);
        ImageManager::delete("/banner/" . json_decode($banner['value'])->image);
        BusinessSetting::where('id', $request->id)->delete();
        return response()->json();
    }

    public function otp_setup()
    {
        $maximum_otp_hit = BusinessSetting::where('type','maximum_otp_hit')->first()->value ?? 0;
        $otp_resend_time = BusinessSetting::where('type','otp_resend_time')->first()->value ?? 0;
        $temporary_block_time = BusinessSetting::where('type','temporary_block_time')->first()->value ?? 0;
        $maximum_login_hit = BusinessSetting::where('type','maximum_login_hit')->first()->value ?? 0;
        $temporary_login_block_time = BusinessSetting::where('type','temporary_login_block_time')->first()->value ?? 0;

        return view('admin-views.business-settings.otp-setup', compact('maximum_otp_hit', 'otp_resend_time',
        'temporary_block_time', 'maximum_login_hit', 'temporary_login_block_time'));
    }

    public function otp_setup_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'maximum_otp_hit'], [
            'value' => $request['maximum_otp_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'otp_resend_time'], [
            'value' => $request['otp_resend_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_block_time'], [
            'value' => $request['temporary_block_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'maximum_login_hit'], [
            'value' => $request['maximum_login_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_login_block_time'], [
            'value' => $request['temporary_login_block_time'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }
    public function statistics_setup()
    {
        $stats = Stat::find(1); 
        if ($stats) {
            $orders = $stats->orders ?? 0;
            $stores = $stats->stores ?? 0;
            $viewers = $stats->viewers ?? 0;
            $returns = $stats->returns ?? 0;
            $customers = $stats->customers ?? 0;
        } else {
            $orders = $stores = $viewers = $returns = $customers = 0;
        }
        return view('admin-views.business-settings.stats-setup', compact('orders', 'stores', 'viewers', 'returns', 'customers'));
    }

    public function statistics_setup_update(Request $request)
    {
        $request->validate([
            'orders' => 'required|integer',
            'stores' => 'required|integer',
            'viewers' => 'required|integer',
            'returns' => 'required|integer',
            'customers' => 'required|integer',
        ]);
    
        $stats = Stat::find(1);
    
        if ($stats) {
            $stats->update([
                'orders' => $request->orders,
                'stores' => $request->stores,
                'viewers' => $request->viewers,
                'returns' => $request->returns,
                'customers' => $request->customers,
            ]);
        } else {
            Stat::create([
                'orders' => $request->orders,
                'stores' => $request->stores,
                'viewers' => $request->viewers,
                'returns' => $request->returns,
                'customers' => $request->customers,
            ]);
        }
        Toastr::success(translate('Statistics updated successfully'));
        return redirect()->route('admin.business-settings.statistics-setup');
    }
    public function features_section()
    {
        $features_section_top = BusinessSetting::where('type', 'features_section_top')->first();
        $features_section_middle = BusinessSetting::where('type', 'features_section_middle')->first();
        $features_section_bottom = BusinessSetting::where('type', 'features_section_bottom')->first();
        return view('admin-views.business-settings.features-section.view', compact('features_section_top','features_section_middle','features_section_bottom'));
    }

    public function features_section_submit(Request $request)
    {
        // features_section_top
        BusinessSetting::updateOrInsert(['type' => 'features_section_top'], [
            'value' => json_encode($request['features_section_top']),
            'created_at' => Carbon::now(),
        ]);

        $section_middle = [];
        if($request->features_section_middle)
        {
            foreach($request->features_section_middle['title'] as $key => $value){
                $section_middle[] = [
                    'title' => $request->features_section_middle['title'][$key] ?? '',
                    'subtitle' => $request->features_section_middle['subtitle'][$key] ?? '',
                ];
            }
        }
        // features_section_middle
        BusinessSetting::updateOrInsert(['type' => 'features_section_middle'], [
            'value' => json_encode($section_middle),
            'created_at' => Carbon::now(),
        ]);

        if($request->features_section_bottom)
        {
            $features_section_bottom = BusinessSetting::where(['type' => 'features_section_bottom'])->first();
            if($features_section_bottom)
            {
                $section_bottom = json_decode($features_section_bottom->value);
            }else{
                $section_bottom = [];
            }
            foreach($request->features_section_bottom['title'] as $key => $value){

                if (!empty($request->features_section_bottom_icon) && isset($request->features_section_bottom_icon[$key]))
                {
                    $image = ImageManager::upload('banner/', 'png', $request->features_section_bottom_icon[$key]);
                } else {
                    $image = '';
                }

                $section_bottom[] = [
                    'title' => $request->features_section_bottom['title'][$key],
                    'subtitle' => $request->features_section_bottom['subtitle'][$key],
                    'icon' => $image,
                ];
            }

            // features_section_bottom
            BusinessSetting::updateOrInsert(['type' => 'features_section_bottom'], [
                'value' => json_encode($section_bottom),
                'created_at' => Carbon::now(),
            ]);

        }

        return back();
    }

    public function features_section_icon_remove(Request $request)
    {
        $data = BusinessSetting::where(['type' => 'features_section_bottom'])->first();

        if($data){
            $new_arr = [];
            foreach(json_decode($data->value) as $item)
            {
                if($request->title != $item->title && $request->subtitle != $item->subtitle){
                    $new_arr[] = $item;
                }else{
                    ImageManager::delete("/banner/" . $item->icon);
                }
            }
            // features_section_bottom
            BusinessSetting::updateOrInsert(['type' => 'features_section_bottom'], [
                'value' => json_encode($new_arr),
            ]);
        }

        return response()->json([
            'status'=>'success'
        ]);
    }

}
