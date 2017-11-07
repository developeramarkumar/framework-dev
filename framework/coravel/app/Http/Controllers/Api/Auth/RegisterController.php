<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Model\Transaction;
use App\Model\User;
use \Request;
use Core\Event;
use App\Events\SmsEvent;
use App\Model\UserOtp;
use Core\Auth;
use App\Model\MonyTransWallet;
use App\Model\RechargeWallet;

use \Hash;
use \Crypt;
class RegisterController extends Controller
{
	public function register(Request $request){
		$validate = $this->validate(Request::Input(),[
			'email' => 'required|email|unique:users',
			'name'=>'required',
			'mobile'=>'required|numeric|unique:users',
			'password'=>'required|min:6',
			'terms-condition'=>'required',
		]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		$used_referinput = Request::input('referid');
		if($used_referinput=="")
		{
			$used_referinput='ETHERDOG';
		}
		
		$used_refer = User::where(['refer_id'=>$used_referinput])->first();
		if(!$used_refer){
			return array('status'=>500,'errors'=>['referid'=>'Refer id not match']);
		}
		$data = array(
			'name'=>Request::input('name'),
			'email'=>Request::input('email'),
			'mobile'=>Request::input('mobile'),
			'referid'=>Request::input('referid'),
			'password'=>Request::input('password'),
		);
		$otp = mt_rand(100000, 999999);
		$mobile=Request::input('mobile');
		$userotp = UserOtp::updateOrCreate(['mobile'=>$mobile],['mobile'=>$mobile,'otp'=>$otp]);
		$message = 'Dear User Your www.etherdoge.net Registration Verification OTP Is : '.$otp;
		if($userotp){
			Event::fire(new SmsEvent(Request::input('mobile'),$message));
			return array('status'=>200,'status_message'=>'Success please enter your OTP');
		}
		return array('status'=>400,'status_message'=>'Whoops ! looks like somthing wrong');
		

		
		
	}
	public function verifyOtp(Request $request){
		Request::all();
	    $mobile=Request::input('mobile_veryfy');
		$otp = Request::input('otp');
		$name = Request::input('name');
		 $email = Request::input('email');
		$mobile = Request::input('mobile');

		$used_referinput = Request::input('referid');
		$referid = 'EDC'.substr(str_replace('.', null, str_replace(' ', null, microtime())), 1,7);
		$validate = $this->validate(Request::Input(),[
			'email' => 'required|email|unique:users',
			'name'=>'required',
			'otp' => [
						'required',
						Rule::exists('user_otps')->where(function ($query) {
				            $query->where('mobile', Request::input('mobile'));
				        }),
					],
			'mobile'=>'required|numeric|unique:users',
			'password'=>'required|min:6',
		]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}

		if($used_referinput=="")
		{
			$used_referinput='ETHERDOG';
		}
		
		$used_refer = User::where(['refer_id'=>$used_referinput])->first();
		if(!$used_refer){
			return array('status'=>500,'errors'=>['referid'=>'Refer id not match']);
		}
		$user_insert = User::create(['name'=>Request::input('name'),'email'=>Request::input('email'),'mobile'=>Request::input('mobile'),'refer_id'=>$referid,'plan_pass'=>\Crypt::encrypt(Request::input('password')),'used_refer'=>$used_refer->id,'password'=>Hash::make(Request::input('password')),'wallet_address'=>str_random(32)]);
		if($user_insert)
		{
			Auth::guard('user')->login($user_insert);
			UserOtp::where('mobile',$user_insert->mobile)->delete();
			$message = 'Congratulations Dear '.$user_insert->name.','.PHP_EOL.'Your Registration Has Been Completed. Please Visit www.etherdoge.net and Login with Mobile No.:'.$user_insert->mobile.PHP_EOL.' and Password : '.\Crypt::decrypt($user_insert->plan_pass);
			Event::fire(new SmsEvent($user_insert->mobile,$message));
			$id = $user_insert->id;
			$amtrefer='20';
			$amt='100';
			
			RechargeWallet::create(['amount'=>$amt,'user_id'=>$id]);
			$log = new Transaction;
			$log->tran_id = str_random(6);
			$log->tran_type = 'Signup Bonus';
			$log->amount	=	$amt;
			$log->credit	=	$user_insert->id;
			$log->debit		=	$user_insert->id;
			$log->save();
			
			$refuser = MonyTransWallet::firstOrNew(['user_id' => $used_refer->id]);
			$refuser->amount = $refuser->amount+$amtrefer;
			$refuser->save();
			
			$log = new Transaction;
			$log->tran_id = str_random(6);
			$log->tran_type = 'Refer Bonus';
			$log->amount	=	$amtrefer;
			$log->credit	=	$used_refer->id;
			$log->debit		=	$user_insert->id;
			$log->save();
			
			return array('status'=>200,'status_message'=>'success');
		}
		else
		{
			return array('status'=>400,'status_message'=>'There is something error');
		}
	
	}
	
	function resend_otp()
	{
		$otp = mt_rand(100000, 999999);
		$mobile=Request::input('mobile');
		$chk_mobile = UserOtp::where(['mobile'=>$mobile])->first();
		if($chk_mobile){
		
		$userotp = UserOtp::updateOrCreate(['mobile'=>$mobile],['mobile'=>$mobile,'otp'=>$otp]);
		$message = 'Your one time verification code is: '.$otp;
		if($userotp){
			Event::fire(new SmsEvent(Request::input('mobile'),$message));
			return array('status'=>200,'status_message'=>'Success please enter your OTP');
		}
		}
		else{
		
		return array('status'=>400,'status_message'=>'This mobile no is not registered with us!!');
		}
	}

    
}
