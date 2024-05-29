<?php

namespace App\Http\Controllers\Admin;

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
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $notifications = Notification::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $notifications = new Notification();
        }
        $notifications = $notifications->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.notification.index', compact('notifications','search'));
    }
    
    public function index_2(Request $request)
    {
        
        return view('admin-views.notification.index-notification');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = new Notification;
        $notification->title = $request->title;
        $notification->description = $request->description;

        if ($request->has('image')) {
            $notification->image = ImageManager::upload('notification/', 'png', $request->file('image'));
        } else {
            $notification->image = 'null';
        }

        $notification->status             = 1;
        $notification->notification_count = 1;
        $notification->save();

        try {
            Helpers::send_push_notif_to_topic($notification);
        } catch (\Exception $e) {
            Toastr::warning('Push notification failed!');
        }

        Toastr::success('Notification sent successfully!');
        return back();
    }

    public function edit($id)
    {
        $notification = Notification::find($id);
        return view('admin-views.notification.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = Notification::find($id);
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = $request->has('image')? ImageManager::update('notification/', $notification->image, 'png', $request->file('image')):$notification->image;
        $notification->save();

        Toastr::success('Notification updated successfully!');
        return redirect('/admin/notification/add-new');
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $notification = Notification::find($request->id);
            $notification->status = $request->status;
            $notification->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function resendNotification(Request $request){
        $notification = Notification::find($request->id);

        $data = array();
        try {
            Helpers::send_push_notif_to_topic($notification);
            $notification->notification_count += 1;
            $notification->save();

            $data['success'] = true;
            $data['message'] = \App\CPU\translate("Push notification successfully!");
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = \App\CPU\translate("Push notification failed!");
        }

        return $data;
    }

    public function delete(Request $request)
    {
        $notification = Notification::find($request->id);
        ImageManager::delete('/notification/' . $notification['image']);
        $notification->delete();
        return response()->json();
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
