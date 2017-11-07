<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\User;
use \Request;
use Core\Auth;
class TransactionHistoryController extends Controller
{
	
   public function free_income(){
     	$user = Auth::api();
    	$free_incomes = Transaction::where(function($query) use ($user){
			$query->where(['credit'=>$user->id,'tran_type'=>'Signup Bonus']);
		})->orWhere(function($query) use ($user){
			$query->where(['credit'=>$user->id,'tran_type'=>'Refer Bonus']);
		})->leftJoin('users as cr','cr.id','=','transction.credit')->orderBy('created_at','desc')->select(['cr.name as name','transction.amount as amount','transction.tran_type as tran_type', 'transction.created_at as created_at'])
		->get();
		if(count($free_incomes)>0){
		return array('status' => 200,'status_message'=>'Success','data'=>$free_incomes);

		}else{
		return array('status' => 400,'status_message'=>'No data Found');	
	}

    }

    public function rupee_wallet_statement()
    {
     	$user = Auth::api();
    	$rupee_wallet= Transaction::where(function($query) use ($user){
			$query->where(['credit'=>$user->id,'tran_type'=>'Rupees To Coin']);
		})->orWhere(function($query) use ($user){
				$query->where(['credit'=>$user->id,'tran_type'=>'Signup Bonus']);
		})->orWhere(function($query) use ($user){
				$query->where(['credit'=>$user->id,'tran_type'=>'Refer Bonus']);
		}) ->select(['amount','tran_type'])->get();
		

		if(count($rupee_wallet)>0){
		return array('status' => 200,'status_message'=>'Success','data'=>$rupee_wallet);

		}else{
		return array('status' => 400,'status_message'=>'No data Found');	
	}

    }


     public function receive_coin_report()
    {
     	$user = Auth::api();
    	$receive_coin= Transaction::where(function($query) use ($user){
			$query->where(['credit'=>$user->id,'tran_type'=>'Rupees To Coin']);
		})->orWhere(function($query) use ($user){
			$query->where(['credit'=>$user->id,'tran_type'=>'user to user']);
		})->leftJoin('users as cr','cr.id','=','transction.credit')
		  ->leftJoin('users as dr','dr.id','=','transction.debit')
		  ->select(['cr.name as transferBy','dr.name as transferTo','transction.amount as amount', 'transction.tran_type as tran_type'])
		  ->get();
		if(count($receive_coin)>0){
			
		return array('status' => 200,'status_message'=>'Success','data'=>$receive_coin);	

		}else{
		return array('status' => 400,'status_message'=>'No data Found');	
	}

    }


     public function transfer_coin_report()
    {
	    $user = Auth::api();
	   	$transfer_coin= Transaction::where(function($query) use ($user){

			$query->where(['debit'=>$user->id,'tran_type'=>'Withdraw']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'user to user']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'Refer Bonus']);
		})->leftJoin('users as cr','cr.id','=','transction.credit')
		  ->leftJoin('users as dr','dr.id','=','transction.debit')
		  ->select(['cr.name as transferBy','dr.name as transferTo','transction.amount as amount', 'transction.tran_type as tran_type'])
		  ->get();

		if(count($transfer_coin)>0){

		return array('status' => 200,'status_message'=>'Success','data'=>$transfer_coin);
		}else{
		return array('status' => 400,'status_message'=>'No data Found');	
	}

    }

      public function coin_wallet_statement()
    {
	    $user = Auth::api();
	   	$transactions = Transaction::where(function($query) use ($user){
			$query->where(['credit'=>$user->id,'tran_type'=>'Rupees To Coin']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'Coin To Shopping Wallet']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'Coin To Money Transfer Wallet']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'Coin To Recharge Wallet']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'user to user']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'Withdraw']);
		})->leftJoin('users as cr','cr.id','=','transction.credit')
		  ->leftJoin('users as dr','dr.id','=','transction.debit')
		  ->select(['cr.name as transferBy','dr.name as transferTo','transction.amount as amount', 'transction.tran_type as tran_type','transction.created_at as date'])
		  ->get();

		if(count($transactions)>0){

		return array('status' => 200,'status_message'=>'Success','data'=>$transactions);

		}else{
		return array('status' => 400,'status_message'=>'No data Found');	
	}

    }


    public function coin_wallet_summary()
    {
      $user = Auth::api();
	   $transactions = Transaction::where(function($query) use ($user){
			$query->where(['credit'=>$user->id,'tran_type'=>'Rupees To Coin']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'Withdraw']);
		})->orWhere(function($query) use ($user){
			$query->where(['debit'=>$user->id,'tran_type'=>'user to user']);
		})->select('tran_type',\DB::raw("SUM(amount) as amount"))->groupBy('tran_type')->get();

		if(count($transactions)>0){

		return array('status' => 200,'status_message'=>'Success','data'=>$transactions);

		}else{
		return array('status' => 400,'status_message'=>'No data Found');	
		}

    }

    public function withdraw_statement()
    {
     	$user = Auth::api();
     	$transactions = Transaction::where(['debit'=>$user->id,'tran_type'=>'Withdraw'])
     					->select(['amount as amount', 'tran_type','created_at as date'])
     					->get();
     	if(count($transactions)>0){
		return array('status' => 200,'status_message'=>'Success','data'=>$transactions);
		}else{
		return array('status' => 400,'status_message'=>'No data Found');	
		}

    }


}
