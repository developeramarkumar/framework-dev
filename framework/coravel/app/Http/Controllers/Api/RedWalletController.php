<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Core\Event;
use App\Events\SmsEvent;
use Carbon\Carbon;
use App\Model\RedWallet;
use App\Model\GreenWallet;
use App\Model\User;
use App\Model\Transaction;
use App\Model\UserOtp;
use \Request;

class RedWalletController extends Controller
{
	public function transactionHistory(){	
		
	}
	


	public function check(){
		$validate = $this->validate(Request::Input(),[
			'user_id' => 'required',
			'amount' => 'required|integer|min:250|max:2500',
			]);
		if ($validate) {
			return array('status'=>500,'errors'=>$validate);
		}
		$id = \Crypt::decrypt(Request::input('user_id'));
		if (!$id) {
			return array('status'=>400,'message'=>'Data not found');
		}
		$user = User::where(['id'=>$id])->first();
		if($user->redWallet->amount<Request::input('amount'))
			{
				return array('status' => 400,'message' =>'You have not sufficient INR. To Transfer');
			}
		if ($user) {
			
				return array('status'=>200,'message'=>'You can transfer amount.');
			
		}
		return array('status'=>400,'message'=>'Invalid crediential');
		
	}
public function transferApi()
		{
			$user_id = \Request::input('user_id');
			$amount = \Request::input('amount');
			$transactionPassword = \Request::input('transactionPassword');
			if($user_id=="" OR $amount=="" OR $transactionPassword=="")
			{
				return array('status'=>400,'message'=>'Parameter Missing');
			}
			if($amount<250)
			{
				return array('status'=>400,'message'=>'The amount must be at least 250.');
			}
			if($amount>25000)
			{
				return array('status'=>400,'message'=>'The amount may not be greater than 25000.');
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
				return array('status'=>400,'message'=>'Invalid crediential.');
			}
			if($user->redWallet->amount<Request::input('amount'))
			{
				return array('status'=>400,'message'=>'You have not sufficient INR. To Transfer');	
			}
			if($user->transaction_pass!=Request::input('transactionPassword'))
			{
				return array('status'=>400,'message'=>'Invalid transaction password.');		
			}
			if($user->transaction_pass!=Request::input('transactionPassword'))
			{
				return array('status'=>400,'message'=>'Invalid transaction password.');	
			}
			$amount = (Request::input('amount')-((Request::input('amount')/100)*15));
			$redWallet = $user->redWallet;
			$redWallet->amount = $redWallet->amount-(Request::input('amount'));
			if($redWallet->save()){

			 	//$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'Rupees To Coin','amount'=>Request::input('amount'),'tran_charge_per'=>'15','tran_charge'=>((Request::input('amount')/100)*15),'credit'=>$id,'debit'=>$id]);
			 	$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'Rupees To Coin','amount'=>Request::input('amount'),'credit'=>$id,'debit'=>$id]);
			 	$mainWallet = GreenWallet::firstOrNew(['user_id'=>$user->id]);
				$mainWallet->amount = $mainWallet->amount+Request::input('amount');
			 	$mainWallet->save();
				return array('status'=>200,'message'=>'Amount transfer successfully..');	
			
			}
			return array('status'=>400,'message'=>'Somthing went wrong ! try again ..');		
		}

	public function transfer()
		{
			
			$validate = $this->validate(Request::Input(),[
				'user_id' => 'required',
				'amount' => 'required|integer|min:250|max:25000',
				'transactionPassword' => 'required|min:6'
			]);
			if ($validate) {
				return $this->json(['errors'=>$validate],500);
			}			
			$id = \Crypt::decrypt(Request::input('user_id'));
			if (!$id) {
				return $this->json(['class'=>'error','message'=>'Invalid crediential'],500);
			}
			$user = User::find($id);
			if (!$user) {
				return $this->json(['class'=>'error','message'=>'Invalid crediential.'],500);
			}
			if($user->redWallet->amount<Request::input('amount'))
			{
				return $this->json(['errors'=>['amount'=>'You have not sufficient INR. To Transfer']],500);
			}
			if($user->transaction_pass!=Request::input('transactionPassword'))
			{
				return $this->json(['errors'=>['transactionPassword'=>'Invalid transaction password.']],500);
				
			}

			$amount = (Request::input('amount')-((Request::input('amount')/100)*15));

			$redWallet = $user->redWallet;
			$redWallet->amount = $redWallet->amount-(Request::input('amount'));
			if($redWallet->save()){

			 	//$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'Rupees To Coin','amount'=>Request::input('amount'),'tran_charge_per'=>'15','tran_charge'=>((Request::input('amount')/100)*15),'credit'=>$id,'debit'=>$id]);
			 	$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'Rupees To Coin','amount'=>Request::input('amount'),'credit'=>$id,'debit'=>$id]);
			 	$mainWallet = GreenWallet::firstOrNew(['user_id'=>$user->id]);
				$mainWallet->amount = $mainWallet->amount+Request::input('amount');
			 	$mainWallet->save();
			 	return $this->json(['class'=>'success','message' =>'Amount transfer successfully..'],200);
			
			}
			return $this->json(['class'=>'error','message'=>'Somthing went wrong ! try again ..'],500);
		}


   
}
