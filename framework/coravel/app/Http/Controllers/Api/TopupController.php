<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use \Request;
use App\Model\Transaction;
use Redirect;

use App\Model\User;
use Core\Auth;
use Core\Event;
use App\Events\SmsEvent;
use App\Model\BlueWallet;
use App\Model\GreenWallet;
use App\Model\RedWallet;
use App\Model\UserPackage;

use App\Model\Ibplan;
use App\Model\Ibtopup;

class TopupController extends Controller
{

    public function topup(){
         $validate = $this->validate(Request::all(),[
            'user_id' => 'required',
            'plan'  => 'required',
            'refer_id' => 'required|exists:users,refer_id',
            'name' => 'required',
            'transactionPassword' => 'required'
        ],[
                'refer_id.exists'=>'The selected user id is invalid. ',
                'refer_id.required'=>'The user id field is required. ',
                'refer_id.size'=>'The user id must be :size characters. '
        ]);
        if ($validate) {
            return array('status'=>500,'errors'=>$validate);
        }
        $id = \Crypt::decrypt(Request::input('user_id'));
        if (!$id) {
            return array('status'=>500,'errors'=>['user_id'=>'Invalid user id ! ']);
        }
        $user = User::where(['id'=>$id])->first();
        if (!$user) {
            return array('status'=>500,'errors'=>['user_id'=>'Invalid user id ! ']);
        }
        
        if (Request::input('transactionPassword') != $user->transaction_pass) {
            return array('status'=>500,'errors'=>['transactionPassword'=>'Invalid transaction password.']);
        } 
        $plan = Ibplan::where('id',Request::input('plan'))->first(); 

        if ($plan->amount>$user->greenWallet->amount) {
            return array('status'=>500,'errors'=>['plan'=>'You have not sufficient amount to transfer.']);
        }

        $plan_present = ($plan->amount*$plan->plan_percentage)/100;
        if ($user->used_refer >1) {
             $parent = User::find($user->used_refer);

        $parent_red = RedWallet::firstOrNew(['user_id'=>$parent->id]);
        $parent_red->amount = $parent_red->amount+$plan_present;
        $parent_red->save();
        }
       

        $toUserData = User::where(['refer_id'=>Request::input('refer_id')])->first(); 

        $userpackage = UserPackage::where('user_id',$toUserData->id)->first();   
        if (@$userpackage->plan_id >= $plan->id) {
            return array('status'=>500,'errors'=>['plan'=>'You can not topup with this plan.']);
        }
        
        $tran = new Transaction;
        $tran->tran_id = str_random(6);
        $tran->tran_type = 'topup';
        $tran->amount = $plan->amount;
        $tran->credit = $toUserData->id;
        $tran->debit = $user->id;
        $tran->save();

        $green = GreenWallet::where('user_id',Auth::guard('user')->user()->id)->first();
        $green->amount = $green->amount-$plan->amount;
        $green->save();


        $UserPackage = UserPackage::firstOrNew(['user_id'=>$toUserData->id]);
        $UserPackage->amount =$plan->amount;
        $UserPackage->plan_id =$plan->id;
        $UserPackage->save();

        

        if ($UserPackage->save()) {
            return json_encode(['status'=>200,'message'=>'Your account has topup successfully!']);
        }

        
        
    }
}
