<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use \Request;
use \Redirect;
use App\Model\User;


class UserController extends Controller
{

	public function getAllPlan(){
		return 'ffdg';
	
	}
	public function getNameApi(){
		
			$user_id = \Request::input('user_id');
			$refer_id = \Request::input('refer_id');
			$existuser = User::where('refer_id',$refer_id)->first();
			if($user_id=="" OR $refer_id=="")
			{
				return array('status'=>400,'message'=>'Parameter Missing');
			}
			if(!$existuser)
			{
				return array('status'=>400,'message'=>'This member id is not exist.');
			}
			$id = \Crypt::decrypt(Request::input('user_id'));
			if (!$id) {
				return array('status'=>400,'message'=>'Invalid member id !');
			}
			 $user = User::where(['id'=>$id])->first();
			if (!$user) {
			return array('status'=>400,'message'=>'Invalid member id !');
			}
			 $refer = User::where(['refer_id'=>Request::input('refer_id')])->select(['name'])->first();
			if (!$refer) {
			return array('status'=>400,'message'=>'Invalid member id !');
			}
			return array('status'=>200,'message'=>'Success','data'=>$refer->toArray());
		
    }   


   public function getName(){
        $validate = $this->validate(Request::all(),[
            'user_id' => 'required',
            'refer_id' => 'required|exists:users,refer_id',           
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
        $refer = User::where(['refer_id'=>Request::input('refer_id')])->select(['name'])->first();
        if (!$refer) {
            return array('status'=>500,'errors'=>['refer_id'=>'Invalid user id ! ']);
        }
        return array('status'=>200,'data'=>$refer->toArray()); 
        
        
    }
}
