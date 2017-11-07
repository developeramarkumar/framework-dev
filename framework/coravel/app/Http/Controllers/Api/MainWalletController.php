<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\GreenWallet;
use App\Model\User;
use Core\Hash;
use \Request;
class MainWalletController extends Controller
{
	public function checkTopup(){
		$this->validate(Request::all(),[
			'bank_name' =>'required|integer',
			'package_name' => 'required',
			'payment_mode' => 'required',
			'transaction_id' => 'required',
			'account_holder_name' => 'required',
			'deposite_from' => 'required',
			'slip_image' => 'required',
		]);
	}
   
}
