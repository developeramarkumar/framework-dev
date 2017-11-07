<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\Contact;
use \Request;
class ContactController extends Controller
{
	
   public function store(Request $request)
    {
		$validate = $this->validate(Request::Input(),[
			'first_name'=>'required',
			'last_name'=>'required',
			'emailid'=>'required',
			'phone'=>'required|numeric',
			'message' => 'required',
			]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		
		
    	$contactUs = Contact::create(['name'=>Request::input('first_name').' '.Request::input('last_name'),'mobile'=>Request::input('phone'),'email'=>Request::input('emailid'),'message'=>Request::input('message')]);

    	 if ($contactUs) {
            return array('status' => 200,'status_message' =>'Thanks for your submitting.We will contact within 24 Hours');
        }
    }
}
