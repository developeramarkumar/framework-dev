<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use Core\Event;
use App\Events\SmsEvent;
use App\Model\User;
use App\Model\UserOtp;
use Carbon\Carbon;
use Core\Auth;
use \Request;
use \Session;
use \Redirect;
use \Hash;
use \Crypt;
use Illuminate\Validation\Rule;
class ForgetPasswordController extends Controller
{
	public function sendOtp(){
		$validate = $this->validate(Request::Input(),[
			'mobile' => 'required|exists:users',			
			]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		$user = User::where(['mobile'=>Request::input('mobile')])->first();	
		if ($user) {
			$otp = mt_rand(100000, 999999);
			$userotp = UserOtp::updateOrCreate(['mobile'=>$user->mobile],['mobile'=>$user->mobile,'otp'=>$otp]);
			$message = 'Your one time verification code is: '.$otp;
			if($userotp){
				Event::fire(new SmsEvent($user->mobile,$message));
				return array('status'=>200,'message'=>'Successfully OTP sent');
			}
		}
		return array('status'=>500,'errors'=>['mobile'=>'Mobile no. not registered with us.']);
		
	}
	public function change(){
		$validate = $this->validate(Request::Input(),[
			'mobile' => 'required|exists:users',
			'password'=>'required|confirmed|min:6',
			'password_confirmation'=>'required|min:6',
			'otp' => [
						'required',
						Rule::exists('user_otps')->where(function ($query) {
				            $query->where('mobile', Request::input('mobile'));
				        }),
					]
			]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		
		UserOtp::where(['mobile'=>Request::input('mobile')])->delete();

		$user = User::where(['mobile'=>Request::input('mobile')])->first();
		$user->password = \Hash::make(Request::input('password'));
		$user->plan_pass = \Crypt::encrypt(Request::input('password'));
		$user->save();
		return array('status'=>200,'status_message'=>'Changed password successfully');
		
	}
	
	private function username(){
		if(is_numeric(Request::input('username'))){
			return 'mobile';
		}
		return 'refer_id';
	}
	public function resend_otp()
	{
		 $otp = mt_rand(100000, 999999);
		 $chk_mobile = User::where([$this->username()=>Request::input('username')])->first();
		if($chk_mobile->mobile == $mobile){		
			$userotp = UserOtp::updateOrCreate(['mobile'=>Request::input('username')],['mobile'=>Request::input('username'),'otp'=>$otp]);
			$message = 'Your one time verification code is: '.$otp;
			if($userotp){
				Event::fire(new SmsEvent(Request::input('username'),$message));
				return array('status'=>200,'status_message'=>'Success please enter your OTP');
			}
		}
		else{
		
		return array('status'=>400,'status_message'=>'This mobile no is not registered with us!!');
		}
	}
    
}
