<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\GreenWallet;
use App\Model\Transaction;
use App\Model\FixedSafe;
use \Request;

class FixedSafeController extends Controller
{
	


public function fixsafevalidate(Request $request)
		{
			$input = Request::input();
			$id = \Crypt::decrypt(Request::input('user_id'));
			$user = User::find($id);
			if(Request::input('amount')=="" OR Request::input('timeperiod')==""){
				return array('status' => 400,'status_message' =>'Parameter missing');
			}
			if(Request::input('amount')<='999')
			{
				return array('status' => 400,'status_message' =>'Minimum 1000 Coin. To Transfer');
			}
			if(@($user->greenWallet->amount) < Request::input('amount'))
			{
				return array('status' => 400,'status_message' =>'You have not sufficient Investment. To Transfer');
			}
			if(Request::input('amount')!='')
			{
				return array('status' => 200,'status_message' =>'success');
			}
		}

public function transfer(Request $request)
		{
			$input = Request::input();
			
			if(Request::input('user_id')=="" OR Request::input('amount')=="" OR Request::input('timeperiod')=="" OR Request::input('tranPass')==""){
				return array('status' => 400,'status_message' =>'Parameter missing');
			}
			$id = \Crypt::decrypt(Request::input('user_id'));

			if (!$id) {
				return array('status'=>400,'message'=>'Data not found');
			}
			$user = User::find($id);

			

			if(@($user->greenWallet->amount) < Request::input('amount'))
			{
				return array('status' => 400,'status_message' =>'You have not sufficient Investment. To Transfer');
			}
			if($user->transaction_pass!=Request::input('tranPass'))
			{
				return array('status' => 400,'status_message' =>'Incorrect transaction password.');
			}
			if($user->transaction_pass!="")
			{
				

				//$fixed_safe = FixedSafe::create(['user_id'=>$id,'amount'=>Request::input('amount'),'fixed_investment'=>Request::input('timeperiod')]);
					
				 	$fixed_safe = FixedSafe::firstOrNew(['user_id'=>$id]);
				 	$fixed_safe->amount = $fixed_safe->amount+(Request::input('amount'));
				 	$fixed_safe->fixed_investment = Request::input('timeperiod');

					 if($fixed_safe->save()){
					//if($fixed_safe){

					 	$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'Fixed Investment','amount'=>Request::input('amount'),'credit'=>$id,'debit'=>$id,'fixed_investment'=>Request::input('timeperiod')]);

					$green_wallet = GreenWallet::firstOrNew(['user_id'=>$id]);
					$green_wallet->amount = $green_wallet->amount-(Request::input('amount'));
					$green_wallet->save();
					 	
					 	return array('status' => 200,'status_message' =>'Successfully Transfer to Fixed Coin');
					}
			}
		}


   
}
