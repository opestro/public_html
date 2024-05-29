<?php

namespace App\Http\Controllers\api\v4\auth;

use App\CPU\Helpers;
use App\CPU\SMS_module;
use App\Http\Controllers\Controller;
use App\Model\PasswordReset;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPassword extends Controller
{
    public function reset_password_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $verification_by = Helpers::get_business_settings('forgot_password_verification');
        $otp_interval_time = Helpers::get_business_settings('otp_resend_time') ?? 1; //minute

        $password_verification_data = PasswordReset::where(['user_type'=>'customer'])->where('identity', 'like', "%{$request['identity']}%")->latest()->first();
        if ($verification_by == 'email') {
            $customer = User::Where(['email' => $request['identity']])->first();
            if (isset($customer)) {
                if(isset($password_verification_data) &&  Carbon::parse($password_verification_data->created_at)->diffInMinutes() < $otp_interval_time){
                    $time= $otp_interval_time - Carbon::parse($password_verification_data->created_at)->diffInMinutes();

                    return response()->json(['message' => 'Please try again after ' .  CarbonInterval::minute($time)->cascade()->forHumans()], 200);
                }else {
                    $token = Str::random(120);
                    $reset_data = PasswordReset::where(['identity' => $customer['email']])->latest()->first();
                    if($reset_data){
                        $reset_data->token = $token;
                        $reset_data->created_at = now();
                        $reset_data->updated_at = now();
                        $reset_data->save();
                    }else{
                        $reset_data = new PasswordReset();
                        $reset_data->identity = $customer['email'];
                        $reset_data->token = $token;
                        $reset_data->user_type = 'customer';
                        $reset_data->created_at = now();
                        $reset_data->updated_at = now();
                        $reset_data->save();
                    }

                    $reset_url = url('/') . '/customer/auth/reset-password?token=' . $token;

                    $emailServices_smtp = Helpers::get_business_settings('mail_config');
                    if ($emailServices_smtp['status'] == 0) {
                        $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
                    }
                    if ($emailServices_smtp['status'] == 1) {
                        Mail::to($customer['email'])->send(new \App\Mail\PasswordResetMail($reset_url));
                        $response = 'Check your email';
                    } else {
                        $response = 'Email failed';
                    }
                    return response()->json(['message' => $response], 200);
                }
            }
        } elseif ($verification_by == 'phone') {
            $customer = User::where('phone', 'like', "%{$request['identity']}%")->first();
            if (isset($customer)) {
                if(isset($password_verification_data) &&  Carbon::parse($password_verification_data->created_at)->diffInMinutes() < $otp_interval_time){
                    $time= $otp_interval_time - Carbon::parse($password_verification_data->created_at)->diffInMinutes();

                    return response()->json(['message' => 'Please try again after ' .  CarbonInterval::minute($time)->cascade()->forHumans()], 200);
                }else {
                    $token = rand(1000, 9999);
                    $reset_data = PasswordReset::where(['identity' => $customer['phone']])->latest()->first();
                    if($reset_data){
                        $reset_data->token = $token;
                        $reset_data->created_at = now();
                        $reset_data->updated_at = now();
                        $reset_data->save();
                    }else{
                        $reset_data = new PasswordReset();
                        $reset_data->identity = $customer['phone'];
                        $reset_data->token = $token;
                        $reset_data->user_type = 'customer';
                        $reset_data->created_at = now();
                        $reset_data->updated_at = now();
                        $reset_data->save();
                    }

                    SMS_module::send($customer->phone, $token);
                    return response()->json(['message' => 'otp sent successfully.'], 200);
                }
            }
        }
        return response()->json(['errors' => [
            ['code' => 'not-found', 'message' => 'user not found!']
        ]], 404);
    }

    public function otp_verification_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $max_otp_hit = Helpers::get_business_settings('maximum_otp_hit') ?? 5;
        $temp_block_time = Helpers::get_business_settings('temporary_block_time') ?? 5; // minute
        $id = $request['identity'];
        $password_reset_token = PasswordReset::where('user_type','customer')->where(['token' => $request['otp']])
            ->where('identity', 'like', "%{$id}%")
            ->first();

        if (isset($password_reset_token)) {
            if (isset($password_reset_token->temp_block_time) && Carbon::parse($password_reset_token->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($password_reset_token->temp_block_time)->DiffInSeconds();

                return response()->json(['errors' => [
                    ['code' => 'not-found', 'message' => 'Please try again after ' . CarbonInterval::minute($time)->cascade()->forHumans()]
                ]], 404);
            }

            return response()->json(['message' => 'otp verified.'], 200);

        } else {
            $password_reset = PasswordReset::where(['user_type' => 'customer'])
                ->where('identity', 'like', "%{$id}%")
                ->latest()
                ->first();

            if ($password_reset) {
                if (isset($password_reset->temp_block_time) && Carbon::parse($password_reset->temp_block_time)->diffInMinutes() <= $temp_block_time) {
                    $time = $temp_block_time - Carbon::parse($password_reset->temp_block_time)->diffInMinutes();

                    $message = 'Please try again after ' . CarbonInterval::minute($time)->cascade()->forHumans();

                } elseif ($password_reset->is_temp_blocked == 1 && Carbon::parse($password_reset->created_at)->diffInMinutes() >= $temp_block_time) {
                    $password_reset->otp_hit_count = 1;
                    $password_reset->is_temp_blocked = 0;
                    $password_reset->temp_block_time = null;
                    $password_reset->updated_at = now();
                    $password_reset->save();

                    $message = 'Invalid otp';

                } elseif ($password_reset->otp_hit_count >= $max_otp_hit && $password_reset->is_temp_blocked == 0) {
                    $password_reset->is_temp_blocked = 1;
                    $password_reset->temp_block_time = now();
                    $password_reset->updated_at = now();
                    $password_reset->save();

                    $time = $temp_block_time - Carbon::parse($password_reset->temp_block_time)->DiffInSeconds();

                    $message = 'Too many attempts. Please try again after ' . CarbonInterval::minute($time)->cascade()->forHumans();

                } else {
                    $password_reset->otp_hit_count += 1;
                    $password_reset->save();

                    $message = 'Invalid_OTP';
                }

                return response()->json(['errors' => [
                    ['code' => 'not-found', 'message' => $message ]
                ]], 404);
            } else {
                return response()->json(['errors' => [
                    ['code' => 'not-found', 'message' => 'Invalid OTP' ]
                ]], 404);
            }
        }

    }

    public function reset_password_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'otp' => 'required',
            'password' => 'required|same:confirm_password|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = DB::table('password_resets')
            ->where('user_type','customer')
            ->where('identity', 'like', "%{$request['identity']}%")
            ->where(['token' => $request['otp']])->first();

        if (isset($data)) {
            DB::table('users')->where('phone', 'like', "%{$data->identity}%")
                ->update([
                    'password' => bcrypt(str_replace(' ', '', $request['password']))
                ]);

            DB::table('password_resets')
                ->where('user_type','customer')
                ->where('identity', 'like', "%{$request['identity']}%")
                ->where(['token' => $request['otp']])->delete();

            return response()->json(['message' => 'Password changed successfully.'], 200);
        }
        return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid token.']
        ]], 400);
    }
}
