<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\SellerWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gregwar\Captcha\CaptchaBuilder;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:seller', ['except' => ['logout']]);
    }

    public function captcha(Request $request,$tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if(Session::has($request->captcha_session_id)) {
            Session::forget($request->captcha_session_id);
        }
        Session::put($request->captcha_session_id, $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function login()
    {
        return view('seller-views.auth.login');
    }
    
     public function seller_login_mobile_application(Request $request)
    {
        session()->put('device', 'mobile');
        if ($request->session()->get('seller_logged_in') === true) {
             return redirect()->route('seller.dashboard.index');
        }else{
             return view('seller-views.auth.login');
        }
    
       
    }
    
    

    public function submit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8'
        ]);

        /*
        $recaptcha = Helpers::get_business_settings('recaptcha');

        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            $request->validate([
                'g-recaptcha-response' => [
                    function ($attribute, $value, $fail) {
                        $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                        $response = $value;
                        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                        $response = \file_get_contents($url);
                        $response = json_decode($response);
                        if (!$response->success) {
                            $fail(\App\CPU\translate('ReCAPTCHA Failed'));
                        }
                    },
                ],
            ]);
        } else {
            if (strtolower($request->default_recaptcha_id_seller_login) != strtolower(Session('default_recaptcha_id_seller_login'))) {
                Session::forget('default_recaptcha_id_seller_login');
                if($request->ajax()) {
                    return response()->json([
                        'errors' => [0=>translate('Captcha Failed')]
                    ]);
                }else {
                    return back()->withErrors(\App\CPU\translate('Captcha Failed'));
                }
            }
        }
        */

        if($request->ajax()) {
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()->all()
                ]);
            }
        }else {
            $validator->validate();
        }

        $se = Seller::where(['email' => $request['email']])->first(['status']);

        if (isset($se) && $se['status'] == 'approved' && auth('seller')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            
            $seller = Seller::find(auth('seller')->id());
            if (!empty($request->fcm_token)) {
                $seller->cm_firebase_token = $request->fcm_token;
            }
            $seller->save();
            
            if($request->ajax()) {
                return response()->json([
                    'redirect_url'=> route('seller.dashboard.index'),
                ]);
            }else{
                Toastr::info('Welcome to your dashboard!');
                if (SellerWallet::where('seller_id', auth('seller')->id())->first() == false) {
                    DB::table('seller_wallets')->insert([
                        'seller_id' => auth('seller')->id(),
                        'withdrawn' => 0,
                        'commission_given' => 0,
                        'total_earning' => 0,
                        'pending_withdraw' => 0,
                        'delivery_charge_earned' => 0,
                        'collected_cash' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                session()->put('seller_logged_in', true);
                return redirect()->route('seller.dashboard.index');
            }

        } elseif (isset($se) && $se['status'] == 'pending') {
            if($request->ajax()) {
                return response()->json([
                    'errors' => [0=>translate('Your account is not approved yet.')]
                ]);
            }else{
                return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['Your account is not approved yet.']);
            }
        } elseif (isset($se) && $se['status'] == 'suspended') {
            if($request->ajax()) {
                return response()->json([
                    'errors' => [0=>translate('Your account has been suspended!')]
                ]);
            }else{
                return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['Your account has been suspended!.']);
            }
        }

        if($request->ajax()) {
            return response()->json([
                'errors' => [0=>translate('Credentials does not match.')]
            ]);
        }else{
            return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
        }
    }

    public function logout(Request $request)
    {
        $device = false;
    
        if ($request->session()->get('device') === 'mobile') {
            $device = true;
        }
    
        auth()->guard('seller')->logout();
        $request->session()->invalidate();

        if ($device) {
            return redirect()->route('seller.auth.seller-mobile-application');
        } else {
            return redirect()->route('seller.auth.login');
        }
    }

}
