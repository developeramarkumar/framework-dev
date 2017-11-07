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
use App\Model\UserOtp;

class UserToUserCoinController extends Controller
{
    public function SendOtp(Request $request){

        $input_data = \Request::input();

        $toUserData = User::where('refer_id',$input_data['user'])->first();
        // return $input_data['inr'];

        $user_data = Auth::guard('user')->user();
        
        if (!$user_data->transaction_pass) {
            return json_encode(['status'=>'400','message'=>'Create your transaction Password before convert coin !']);
        }

        $green_wallet = $user_data->greenWallet;

        if (!$green_wallet) {
            return json_encode(['status'=>'400','message'=>'You have not green wallet amount']);

        } else if ($green_wallet->amount <= $input_data['coin']) {

            return json_encode(['status'=>'400','message'=>'Your amount is not valid !']);

        }else{
            // $otp = rand(100000,999999);
            // $message = 'Your otp is: '.$otp.' to send coin.';

            // $sms = Event::fire(new SmsEvent($user_data->mobile,$message));


            //   $otp_save = UserOtp::firstOrNew(['mobile'=>$user_data->mobile]);
            //   // $otp_save->mobile = $user_data->mobile;
            //   $otp_save->otp = $otp;
            //   $otp_save->save();

            $tran = new Transaction;
            $tran->tran_id = rand(100000000,9999999999);
            $tran->tran_type = 'coin to user';
            $tran->amount = $input_data['coin'];
            // $tran->user_id = $user_data->id;
            $tran->credit = $toUserData->id;
            $tran->debit = $user_data->id;
            $tran->save();


            return json_encode(['status'=>200,'message'=>'success','tran_id'=>$tran->id]);
        }        
    }

    public function veryPassword(){
        $input_data = \Request::input();

        // $user_otp = UserOtp::where('mobile',Auth::guard('user')->user()->mobile)->first();

        if ($input_data['tranPass'] == Auth::guard('user')->user()->transaction_pass) {
                

            $tran_data = Transaction::find($input_data['tran_id']);

            $toUserData = User::find($tran_data->credit);



            $bluwallet = GreenWallet::where('user_id',Auth::guard('user')->user()->id)->first();
            $bluwallet->amount = $bluwallet->amount-$tran_data->amount;
            $bluwallet->save();

            $green = GreenWallet::firstOrCreate(['user_id'=>$toUserData->id]);
            $green->amount = $green->amount+$tran_data->amount;
            $green->save();

            // Event::fire(new SmsEvent($toUserData->mobile,'You have received '.$tran_data->amount.' coin from '.Auth::guard('user')->user()->name));

            if ($green) {
                return json_encode(['status'=>200,'message'=>'Successfuly transferd Coin !']);
            }

        } else {

            return json_encode(['status'=>400,'message'=>'Your otp or transaction not match !']);
        }
        
    }
}
