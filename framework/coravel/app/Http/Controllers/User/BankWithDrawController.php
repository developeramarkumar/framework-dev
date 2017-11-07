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

use App\Model\UserOtp;

use App\Model\Redeem;
use Core\Session;
class BankWithDrawController extends Controller
{
    public function SendOtp(Request $request){

        $input_data = \Request::input();


        $user_data = Auth::guard('user')->user();
        
        if (!$user_data->transaction_pass) {
            return json_encode(['status'=>'400','message'=>'Create your transaction Password before withdraw !']);
        }

        $redWallet = $user_data->redWallet;

        if (!$redWallet) {
            return json_encode(['status'=>'400','message'=>'You have not green wallet amount']);

        } else if ($redWallet->amount <= $input_data['amount']) {

            return json_encode(['status'=>'400','message'=>'Your amount is not valid !']);

        }else{
            // $otp = rand(100000,999999);
            // $message = 'Your otp is: '.$otp.' to bank withdraw.';

            // $sms = Event::fire(new SmsEvent($user_data->mobile,$message));


            // $otp_save = UserOtp::firstOrNew(['mobile'=>$user_data->mobile]);
            // // $otp_save->mobile = $user_data->mobile;
            // $otp_save->otp = $otp;
            // $otp_save->save();

            $tran = new Transaction;
            $tran->tran_id = rand(1000000000,9999999999);
            $tran->tran_type = 'Withdraw';
            $tran->amount = $input_data['amount'];
            // $tran->user_id = $user_data->id;
            $tran->credit = $user_data->id;
            $tran->debit = '1';
            $tran->save();


            return json_encode(['status'=>200,'message'=>'success','tran_id'=>$tran->id]);
        }        
    }

    public function transfer(){
        // return Request::all();
        $validate = $this->validate(Request::all(),[
            'withdraw_balance' => 'required|integer|min:500',
            'transaction_password' => 'required'
        ]);
        if ($validate) 
        {
          Session::withInput(Request::all());
          Session::withError($validate);
          return Redirect::back();
        }
        // $user_otp = UserOtp::where('mobile',Auth::guard('user')->user()->mobile)->first();

        if (Request::input('transaction_password') != Auth::guard('user')->user()->transaction_pass) {
                Session::withInput(Request::all());
                Session::withError(['transaction_password'=>'Invalid transaction password.']);
                return Redirect::back(); 
        }

        $green = GreenWallet::firstOrCreate(['user_id'=>Auth::guard('user')->user()->id]);

        if ((int)$green->amount <= Request::input('withdraw_balance')) {
            Session::withFlash(['message'=>'Amount is not valid!','class'=>'error']);
            return Redirect::back();
        }

        $green->amount = ($green->amount-Request::input('withdraw_balance'));

        $green->save();
        // return $green->amount;

        

        $re = new Redeem;
        $re->user_id = Auth::guard('user')->user()->id;
        $re->amount = (Request::input('withdraw_balance')-((Request::input('withdraw_balance')/100)*15));
        $re->status = '0';

        $tran = new Transaction;
        $tran->tran_id = rand(1000000000,9999999999);
        $tran->tran_type = 'Withdraw';
        $tran->amount = Request::input('withdraw_balance');
        $tran->credit = '1';
        $tran->debit = Auth::guard('user')->user()->id;
        $tran->save();
        // $tran;
        if ($re->save()) {
            # code...
        }
        Session::withFlash(['message'=>'Your request has been receved!','class'=>'success']);
        return Redirect::back();

            

       
        
    }
}
