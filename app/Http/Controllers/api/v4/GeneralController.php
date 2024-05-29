<?php

namespace App\Http\Controllers\api\v4;

use App\Http\Controllers\Controller;
use App\Model\HelpTopic;
use App\Model\SocialMedia;
use App\Model\Subscription;
use Illuminate\Http\Request;
use function App\CPU\translate;

class GeneralController extends Controller
{
    public function faq(){
        return response()->json(HelpTopic::orderBy('ranking')->get(),200);
    }

    public function subscription(Request $request)
    {
        $subscription_email = Subscription::where('email',$request->subscription_email)->first();
        if($subscription_email){
            return response()->json(['status'=>'subscribed',200]);

        }else{
            $new_subcription = new Subscription;
            $new_subcription->email = $request->subscription_email;
            $new_subcription->save();

            return response()->json(['status'=>'success',200]);
        }

    }

    public function social_media(){
        $socials = SocialMedia::where(['active_status'=>1])->get();

        return response()->json(['socials'=>$socials, 200]);
    }
}
