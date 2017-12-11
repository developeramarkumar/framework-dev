<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Contact;
class ContactController extends Controller
{
    public function store(Request $request)
    {
    	$contactUs = Contact::create(['name'=>$request->name,'mobile'=>$request->mobile,'subject'=>$request->subject,'message'=>$request->message]);

    	 if ($contactUs) {
            return array('status' => 200,'status_message' =>'Thanks for your submitting.We will contact within 24 Hours');
        }
    }
}
