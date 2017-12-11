<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\Contact;
use \Request;
class treelavelController extends Controller
{
	
   public function store(Request $request)
    {
    	$contactUs = Contact::create(['name'=>Request::input('name'),'mobile'=>Request::input('mobile'),'subject'=>Request::input('subject'),'message'=>Request::input('message')]);

    	 if ($contactUs) {
            return array('status' => 200,'status_message' =>'Thanks for your submitting.We will contact within 24 Hours');
        }
    }
}
