<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\HelpTopic;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function helpTopic()
    {
        $helps = HelpTopic::Status()->latest()->get();
        return view(VIEW_FILE_NAMES['faq'], compact('helps'));
    }
    
    public function steps_seller()
    {
       
        return view(VIEW_FILE_NAMES['steps_seller']);
    }
     public function selling_on_nichen()
    {
       
        return view(VIEW_FILE_NAMES['selling-on-nichen']);
    }
    
    
    
    
        public function offers()
    {
       
        return view(VIEW_FILE_NAMES['our_offers']);
    }

    
    public function contacts()
    {
        $recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha');
        return view(VIEW_FILE_NAMES['contacts'],compact('recaptcha'));
    }

    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();
        return view(VIEW_FILE_NAMES['about_us'], [
            'about_us' => $about_us,
        ]);
    }

    public function termsand_condition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();
        return view(VIEW_FILE_NAMES['terms_conditions_page'], compact('terms_condition'));
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();
        return view(VIEW_FILE_NAMES['privacy_policy_page'], compact('privacy_policy'));
    }

    public function refund_policy()
    {
        $refund_policy = json_decode(BusinessSetting::where('type', 'refund-policy')->first()->value);
        if(!$refund_policy->status){
            return back();
        }
        $refund_policy = $refund_policy->content;
        return view(VIEW_FILE_NAMES['refund_policy_page'], compact('refund_policy'));
    }

    public function return_policy()
    {
        $return_policy = json_decode(BusinessSetting::where('type', 'return-policy')->first()->value);
        if(!$return_policy->status){
            return back();
        }
        $return_policy = $return_policy->content;
        return view(VIEW_FILE_NAMES['return_policy_page'], compact('return_policy'));
    }

    public function cancellation_policy()
    {
        $cancellation_policy = json_decode(BusinessSetting::where('type', 'cancellation-policy')->first()->value);
        if(!$cancellation_policy->status){
            return back();
        }
        $cancellation_policy = $cancellation_policy->content;
        return view(VIEW_FILE_NAMES['cancellation_policy_page'], compact('cancellation_policy'));
    }
}
