<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\GreenWallet;
use App\Model\User;
use App\Model\Transaction;
use \Request;

class WalletController extends Controller
{

	public function transferApi()
		{
			//return 'sadaaaa';
			$user_id = \Request::input('user_id');
			$refer_id = \Request::input('refer_id');
			$amount = \Request::input('amount');
			$transactionPassword = \Request::input('transactionPassword');
			$existuser = User::where('refer_id',$refer_id)->first();
			if($user_id=="" OR $refer_id=="" OR $amount=="" OR $transactionPassword=="")
			{
				return array('status'=>400,'message'=>'Parameter Missing');
			}
			if($amount<250)
			{
				return array('status'=>400,'message'=>'The amount must be at least 250.');
			}
			if($amount>2500)
			{
				return array('status'=>400,'message'=>'The amount may not be greater than 25000.');
			}
			if(!$existuser)
			{
				return array('status'=>400,'message'=>'This member id is not exist.');
			}
			if (!filter_var($amount, FILTER_VALIDATE_INT)) {
				return array('status'=>400,'message'=>'The amount must be integer value.');
			} 
			if(strlen($transactionPassword)<6)
			{
				return array('status'=>400,'message'=>'The transaction password must be at least 6 characters.');
			}
			$id = \Crypt::decrypt(Request::input('user_id'));
			if (!$id) {
				return array('status'=>400,'message'=>'Invalid crediential.');
			}
			$user = User::find($id);
			if (!$user) {
				return array('status' => 400,'message' =>'Invalid crediential.');
			}
			if($user->greenWallet->amount<Request::input('amount'))
			{
				return array('status' => 400,'message' =>'You have not sufficient coin. To Transfer');
			}
			if($user->transaction_pass!=Request::input('transactionPassword'))
			{
				return array('status' => 400,'message' =>'Invalid transaction password ! try again ..');
			}
			$mainWallet = $user->greenWallet;
			$mainWallet->amount = $mainWallet->amount-(Request::input('amount'));

			 if($mainWallet->save()){


			 
				$refUser = User::where(['refer_id'=>Request::input('refer_id')])->first();
				$wallet = GreenWallet::firstOrNew(['user_id'=>$refUser->id]);
				$wallet->amount = $wallet->amount+(Request::input('amount'));
				$wallet->save();

			 	$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'user to user','amount'=>Request::input('amount'),'credit'=>$refUser->id,'debit'=>$id]);	


			 	return array('status' => 200,'message' =>'Amount transfer successfully..');
			}
			return array('status'=>400,'message'=>'Somthing went wrong ! try again ..');
			
		}

	
	
	
	public function transfer()
		{
			// return Request::all();
			$validate = $this->validate(Request::Input(),[
				'user_id' => 'required',
				'refer_id' => 'required|exists:users,refer_id',
				'amount' => 'required|integer|min:250|max:2500',
				'transactionPassword' => 'required|min:6'
			],[
				'refer_id.exists'=>'The selected member id is invalid. ',
				'refer_id.required'=>'The member id field is required. ',
				'refer_id.size'=>'The member id must be :size characters. '
			]);
			if ($validate) {
				return array('status'=>500,'errors'=>$validate);
			}
			
			$id = \Crypt::decrypt(Request::input('user_id'));
			if (!$id) {
				return array('status'=>400,'message'=>'Invalid crediential.');
			}
			$user = User::find($id);
			if (!$user) {
				return array('status' => 400,'message' =>'Invalid crediential.');
			}
			if($user->greenWallet->amount<Request::input('amount'))
			{
				return array('status' => 400,'message' =>'You have not sufficient coin. To Transfer');
			}
			if($user->transaction_pass!=Request::input('transactionPassword'))
			{
				return array('status' => 500,'errors'=>['transactionPassword'=>'Invalid transaction password ! try again ..']);
			}
			// return $user->mainWallet->amount;

			// $amount = (Request::input('amount')-((Request::input('amount')/100)*1));

			$mainWallet = $user->greenWallet;
			$mainWallet->amount = $mainWallet->amount-(Request::input('amount'));

			 if($mainWallet->save()){


			 
				$refUser = User::where(['refer_id'=>Request::input('refer_id')])->first();
				$wallet = GreenWallet::firstOrNew(['user_id'=>$refUser->id]);
				$wallet->amount = $wallet->amount+(Request::input('amount'));
				$wallet->save();

			 	$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'user to user','amount'=>Request::input('amount'),'credit'=>$refUser->id,'debit'=>$id]);	


			 	return array('status' => 200,'message' =>'Amount transfer successfully..');
			}
				
			return array('status'=>400,'message'=>'Somthing went wrong ! try again ..');
		}


   
}
