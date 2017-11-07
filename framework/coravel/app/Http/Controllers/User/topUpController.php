<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;
// use App\Model\Userdetail;
// use App\Model\Bank;
use App\Model\Transaction;
use Redirect;
// use Core\Session;

use App\Model\User;
use Core\Auth;
use Core\Event;
use App\Events\SmsEvent;
use App\Model\BlueWallet;
use App\Model\GreenWallet;
use App\Model\RedWallet;
use App\Model\UserPackage;

use App\Model\UserOtp;
use App\Model\Ibplan;
use App\Model\Ibtopup;

class topUpController extends Controller
{
    public function SendOtp(Request $request){

        $input_data = \Request::input();


        $user_data = Auth::guard('user')->user();

        $plan = Ibplan::where('id',$input_data['plan'])->first();

        $toUserData = User::where('refer_id',$input_data['user'])->first();
        
        if (!$user_data->transaction_pass) {
            return json_encode(['status'=>'400','message'=>'Create your transaction Password before withdraw !']);
        }

        $green = $user_data->greenWallet;

        if (!$green) {
            return json_encode(['status'=>'400','message'=>'You have not green wallet amount']);

        } else if ($green->amount <= $plan->amount) {

            return json_encode(['status'=>'400','message'=>'Your plan is not valid !']);

        }else{
            // $otp = rand(100000,999999);
            // $message = 'Your otp is: '.$otp.' to topup wallet.';

            // $sms = Event::fire(new SmsEvent($user_data->mobile,$message));


            // $otp_save = UserOtp::firstOrNew(['mobile'=>$user_data->mobile]);
            // // $otp_save->mobile = $user_data->mobile;
            // $otp_save->otp = $otp;
            // $otp_save->save();

            $tran = new Transaction;
            $tran->tran_id = str_random(6);
            $tran->tran_type = 'topup';
            $tran->amount = $plan->amount;
            // $tran->user_id = $user_data->id;
            $tran->credit = $toUserData->id;
            $tran->debit = $user_data->id;
            $tran->save();


            return json_encode(['status'=>200,'message'=>'success','tran_id'=>$tran->id,'top_id'=>$input_data['plan']]);
        }        
    }

    public function veryPassword(){
        $input_data = \Request::input();

        // Auth::guard('user')->user()->transaction_pass;

        $plan = Ibplan::where('id',$input_data['topup_id'])->first();

        // $user_otp = UserOtp::where('mobile',Auth::guard('user')->user()->mobile)->first();

         if ($input_data['tranPass'] != Auth::guard('user')->user()->transaction_pass) {
            return json_encode(['status'=>400,'message'=>'Your transaction not match !']);
        }      

            $tran_data = Transaction::find($input_data['tran_id']);

            $toUserData = User::find($tran_data->credit);

            $top = new Ibtopup;
            $top->user_id = Auth::guard('user')->user()->id;
            $top->plan_id = $plan->id;
            $top->to_id = $toUserData->id;
            $top->amount  = $plan->amount;
            $top->name  = $plan->name;
            $top->save();

          

            $green = GreenWallet::where('user_id',Auth::guard('user')->user()->id)->first();
            $green->amount = $green->amount-$tran_data->amount;
            $green->save();


            $UserPackage = UserPackage::firstOrNew(['user_id'=>$toUserData->id]);
            $UserPackage->amount =$tran_data->amount;
            $UserPackage->plan_id =$plan->id;
            $UserPackage->save();

            

            if ($UserPackage->save()) {
                return json_encode(['status'=>200,'message'=>'Your account has topup successfully!']);
            }

        } 
        
        
    }
}
