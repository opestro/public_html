<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Auth;

class NotificationController extends Controller
{
   
    public function index_2(Request $request)
    {
        
        return view('admin-views.notification.index-notification');
    }
    
   public function updateDeviceToken(Request $request)
    {
        $user = User::find(994); 
    
        if ($user) {
            $user->cm_firebase_token = $request->token;
            $user->save();
    
            return response()->json(['Token successfully stored.']);
        } else {
            return response()->json(['User not found.'], 404);
        }
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('cm_firebase_token')->pluck('cm_firebase_token')->all();
            
        $serverKey = 'AAAAH6l6PdM:APA91bFPfP-xvSixRRCfsNKXyEP4hNCae79vPEXVRVaNZqpOhepIkjvc_-0l8Ex7R51GVV1asUYyRxq9y-jAN0n62oSh9RdSzorwiuYRE0eM5d3Sy4CZIDE05SxLw4RVM1yAONUMQ9kV'; 
    
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }

}
