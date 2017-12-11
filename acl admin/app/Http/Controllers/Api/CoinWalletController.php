<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\GreenWallet;
use App\Model\User;
use App\Model\Transaction;
use App\Model\MonyTransWallet;
use App\Model\RechargeWallet;
use App\Model\ShoppingWallet;
use \Request;

class CoinWalletController extends Controller
{

	public function transfer()
		{
			// return Request::all();
			$validate = $this->validate(Request::Input(),[
				'user_id' => 'required',
				// 'refer_id' => 'required|refer_id',
				'amount' => 'required|integer|min:250|max:2500',
				'transactionPassword' => 'required|min:6'
			],[
				// 'refer_id.exists'=>'The selected user id is invalid. ',
				// 'refer_id.required'=>'The user id field is required. ',
				// 'refer_id.size'=>'The user id must be :size characters. '
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
			$green = $user->greenWallet;

			if ($green) {
				$inpt = \Request::all();

				if ($green->amount<=$inpt['amount']) {
					return array('status'=>400,'message'=>'You have not coin to transfer ..');
				}

				if ($inpt['wallet_type'] == 'MoneyTransfer') {

					$gr = $green;
					$gr->amount = $gr->amount-$inpt['amount'];
					$gr->save();

					$moTr = MonyTransWallet::firstOrNew(['user_id'=>$user->id]);
					$moTr->amount = $moTr->amount+$inpt['amount'];
					$moTr->save();

					$tran = new Transaction;
		            $tran->tran_id = rand(100000,999999);
		            $tran->tran_type = 'Coin To Money Transfer Wallet';
		            $tran->amount = $inpt['amount'];
		            $tran->credit = $user->id;
		            $tran->debit = $user->id;
		            $tran->save();

					return array('status' => 200,'message' =>'Amount transfer successfully..');

				}elseif($inpt['wallet_type']=='Recharge'){

					$gr = $green;
					$gr->amount = $gr->amount-$inpt['amount'];
					$gr->save();

					$rech = RechargeWallet::firstOrNew(['user_id'=>$user->id]);
					$rech->amount = $rech->amount+$inpt['amount'];
					$rech->save();

					$tran = new Transaction;
		            $tran->tran_id = rand(100000,999999);
		            $tran->tran_type = 'Coin To Recharge Wallet';
		            $tran->amount = $inpt['amount'];
		            $tran->credit = $user->id;
		            $tran->debit = $user->id;
		            $tran->save();

					return array('status' => 200,'message' =>'Amount transfer successfully..');

				}elseif($inpt['wallet_type'] == 'Shopping'){

					$gr = $green;
					$gr->amount = $gr->amount-$inpt['amount'];
					$gr->save();

					$shop = ShoppingWallet::firstOrNew(['user_id'=>$user->id]);
					$shop->amount = $shop->amount+$inpt['amount'];
					$shop->save();

					$tran = new Transaction;
		            $tran->tran_id = rand(100000,999999);
		            $tran->tran_type = 'Coin To Shopping Wallet';
		            $tran->amount = $inpt['amount'];
		            $tran->credit = $user->id;
		            $tran->debit = $user->id;
		            $tran->save();

					return array('status' => 200,'message' =>'Amount transfer successfully..');
				}else{
					return array('status'=>400,'message'=>'Somthing went wrong ! try again ..');
				}
			}

			// $amount = (Request::input('amount')-((Request::input('amount')/100)*1));

			/*$mainWallet = $user->greenWallet;
			$mainWallet->amount = $mainWallet->amount-(Request::input('amount'));

			 if($mainWallet->save()){


			 
				$refUser = User::where(['refer_id'=>Request::input('refer_id')])->first();
				$wallet = GreenWallet::firstOrNew(['user_id'=>$refUser->id]);
				$wallet->amount = $wallet->amount+($amount);
				$wallet->save();

			 	$transaction = Transaction::create(['tran_id'=>str_random(6),'tran_type'=>'user to user','amount'=>Request::input('amount')-((Request::input('amount')/100)*1),'tran_charge_per'=>'1','tran_charge'=>((Request::input('amount')/100)*1),'credit'=>$refUser->id,'debit'=>$id]);	


			 	return array('status' => 200,'message' =>'Amount transfer successfully..');
			}*/
				
			return array('status'=>400,'message'=>'Somthing went wrong ! try again ..');
		}


   
}
