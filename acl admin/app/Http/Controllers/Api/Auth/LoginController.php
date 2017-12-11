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
use \App\Model\LiveRate;
use \Hash;
use \Crypt;
class LoginController extends Controller
{
	public function login(){
		
		
		$validate = $this->validate(Request::Input(),[
			'username' => 'required',
			'password'=>'required',
			]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		
		if (Auth::guard('user')->attempt([$this->username()=>Request::input('username'),'password'=>Request::input('password')])) {
			$user = Auth::guard('user')->user();
			$user->save();
			

		return array('status'=>200,'message'=>'Successfully login','data'=>['id'=>\Crypt::encrypt($user->id)]);
		}
		return array('status'=>400,'message'=>'Invalid crediential');		
	}
	
	public function logout(){
		Auth::guard('user')->logout();
		return array('status'=>200,'messge'=>'logout successfully');
	}
	
	private function username(){
		if(is_numeric(Request::input('username'))){
			return 'mobile';
		}
		// return 'refer_id';
	}
	
    
}
