<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\Franchisee;
use \Request;
class FranchiseeController extends Controller
{
	
   public function franchisee(Request $request)
    {
		$validate = $this->validate(Request::Input(),[
			'name'=>'required',
			'email'=>'required',
			'telephone'=>'required|numeric',
			'message' => 'required',
			]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		
		
    	$franchisee = Franchisee::create(['fullname'=>Request::input('name'),'mobile'=>Request::input('telephone'),'email'=>Request::input('email'),'message'=>Request::input('message')]);

    	 if ($franchisee) {
            return array('status' => 200,'status_message' =>'Thanks for your submitting.We will contact within 24 Hours');
        }
    }
}
