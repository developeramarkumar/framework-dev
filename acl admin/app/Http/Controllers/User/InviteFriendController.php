<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;

use Redirect;
use Core\Auth;
use Core\Event;
use App\Events\SmsEvent;
use Core\Mail;


class InviteFriendController extends Controller
{
    public function InviteFriend(Request $request){
      $input_data = Request::input();

      $emails = explode(',', $input_data['email']);

      $mail = Mail::to($input_data['email'])->subject($input_data['subject'])->message($input_data['message'])->send();

      if (!$mail) {
        return json_encode(['status'=>200,'message'=>'Your invitation mail has been send!']);
      } else {
        return json_encode(['status'=>400,'message'=>'Sorry something went wrong try again!']);
      }
      
      

    }
}
