<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;
use App\Model\Bank;
use Redirect;
use Core\Auth;
use Core\Session;
use App\Model\DepositeSlip;
class BankDepositeController extends Controller
{
    public function store(){
    
        $validate = $this->validate(Request::all(),[
            'bank_name' =>'required|integer',
            'package_name' => 'required',
            'payment_mode' => 'required',
            'transaction_id' => 'required',
            'account_holder_name' => 'required',
            'deposite_from' => 'required',
            'slip_image' => 'required|mimes:jpeg,bmp,png,gif,pdf',
        ]);
        if ($validate) 
          {
          Session::withInput(Request::all());
          Session::withError($validate);
          return Redirect::back();
        }

        $con=strtolower(trim(Request::input('slip_image')));
        $string=str_replace(" ","-",$con);
        $string =preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $string);
        $fileaadhar=preg_replace('/-+/', '-', $string);
        $aadhar_tmp=$_FILES['slip_image']['tmp_name'];
        $folderpath = "../dashboard/deposite-bank-slip/";
        $filename = str_random(50).'.'.Request::file('slip_image')->extension();
        $aadharpath=$folderpath.$filename;
        move_uploaded_file( $aadhar_tmp,$aadharpath);

        $depositeSlip = new DepositeSlip();
        $depositeSlip->user_id = Auth::guard('user')->user()->id;
        $depositeSlip->bank_id = Request::input('bank_name');
        $depositeSlip->payment_mode = Request::input('payment_mode');
        $depositeSlip->ac_holder_name = Request::input('account_holder_name');
        $depositeSlip->transaction_id = Request::input('transaction_id');
        $depositeSlip->deposite_from = Request::input('deposite_from');
        $depositeSlip->package_name = Request::input('deposite_from');
        $depositeSlip->slip_image = $filename;
        if ($depositeSlip->save()) {
           Session::withFlash(['message'=>'Request sent successfully...','class'=>'success']);
           return Redirect::back();
        }
        Session::withFlash(['message'=>'somthing wrong ! try again ...','class'=>'error']);
           return Redirect::back();

    }

 
}
