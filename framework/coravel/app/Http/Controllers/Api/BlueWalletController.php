<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\BlueWallet;
use App\Model\User;
use App\Model\UserOtp;
use \Request;
use \DB;
class BlueWalletController extends Controller
{
	public function transactionHistory($id){
		return $query = DB::select(DB::raw("SELECT * FROM `transction` WHERE (`tran_type`='Signup Bonus' OR `tran_type`='refer Bonus') AND `user_id`=$id"));

	}

   public function sendOtp(){
		$validate = $this->validate(Request::Input(),[
			'amount' => 'required',
			]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		
		$user = User::where(['id'=>Request::input('user_id')])->first();
		if ($user) {
			$otp = mt_rand(100000, 999999);

			$userotp = UserOtp::updateOrCreate(['mobile'=>$user->mobile],['mobile'=>$user->mobile,'otp'=>$otp]);
			$message = 'Your one time verification code is: '.$otp;
			if($userotp){
				Event::fire(new SmsEvent($user->mobile,$message));
				return array('status'=>200,'message'=>'Successfully OTP sent');
			}
		}
		return array('status'=>400,'message'=>'Invalid crediential');
		
	}

	public function transfer(){
		// $validate = $this->validate(Request::Input(),[
		// 	'username' => 'required',
		// 	'password'=>'required',
		// 	'otp' => 'required',
		// 	]);
		// if ($validate) {
		// 	return array('status'=>500,'errors'=>$validate);
		// }
		$user = User::where([$this->username()=>Request::input('username'),'password'=>Request::input('password')])->first();
		if (!$user) {
			return array('status'=>400,'message'=>'Invalid crediential');
		}
		$otp = UserOtp::where(['mobile'=>$user->mobile,'otp'=>Request::input('otp')])->first();

		if($otp){
			Auth::guard('user')->login($user);
			UserOtp::where('mobile',$user->mobile)->delete();
			return array('status'=>200,'message'=>'login successfully','data'=>['id'=>$user->id,'name'=>$user->name]);
		}
		return array('status'=>400,'message'=>'otp not match');
		
	}
	

}

