<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Transaction;
use App\Model\GreenWallet;
use App\Model\RechargeWallet;
use App\Model\ShoppingWallet;
use App\Model\MonyTransWallet;

use core\Auth;

use \Request;

class AddCoinController extends Controller
{
   
   public function addCoin(Request $request){
      $inpt = \Request::all();
      $use = User::where('refer_id',$inpt['userid'])->first();
      if (count($use)) {

         if ($inpt['wallet'] == 'recharge') {

            $rec = RechargeWallet::firstOrNew(['user_id'=>$use->id]);
            $rec->amount = $rec->amount + $inpt['coin'];
            $rec->save();

            $tran = new Transaction;
            $tran->tran_id = rand(100000,999999);
            $tran->tran_type = 'Add by admin';
            $tran->amount = $inpt['coin'];
            $tran->credit = $use->id;
            $tran->debit = '1';
            $tran->save();

            return array('status'=>200,'message'=>'Coin Added Successfully!','data'=>'ok');

         } elseif($inpt['wallet'] == 'green') {
            
            $gree = GreenWallet::firstOrNew(['user_id'=>$use->id]);
            $gree->amount = $gree->amount+$inpt['coin'];
            $gree->save();

            $tran = new Transaction;
            $tran->tran_id = rand(100000,999999);
            $tran->tran_type = 'Add by admin';
            $tran->amount = $inpt['coin'];
            $tran->credit = $use->id;
            $tran->debit = '1';
            $tran->save();

            return array('status'=>200,'message'=>'Coin Added Successfully!','data'=>'ok');

         }elseif($inpt['wallet'] == 'shopping'){

            $shop = ShoppingWallet::firstOrNew(['user_id'=>$use->id]);
            $shop->amount = $shop->amount+$inpt['coin'];
            $shop->save();

            $tran = new Transaction;
            $tran->tran_id = rand(100000,999999);
            $tran->tran_type = 'Add by admin';
            $tran->amount = $inpt['coin'];
            $tran->credit = $use->id;
            $tran->debit = '1';
            $tran->save();

            return array('status'=>200,'message'=>'Coin Added Successfully!','data'=>'ok');

         }elseif($inpt['wallet'] == 'monyTrans'){

            $monyT = MonyTransWallet::firstOrNew(['user_id'=>$use->id]);
            $monyT->amount = $monyT->amount+$inpt['coin'];
            $monyT->save();

            $tran = new Transaction;
            $tran->tran_id = rand(100000,999999);
            $tran->tran_type = 'Add by admin';
            $tran->amount = $inpt['coin'];
            $tran->credit = $use->id;
            $tran->debit = '1';
            $tran->save();

            return array('status'=>200,'message'=>'Coin Added Successfully!','data'=>'ok');

         }else{
            return array('status'=>400,'message'=>'Sorry something went wrong','data'=>'ok');
         }
         

         // $wallet = $use->greenWallet;

         // if ($wallet) {
         //    $wallet->amount = $wallet->amount+$inpt['coin'];
         //    $wallet->save();
         // }else{
         //    $wallet = GreenWallet::firstOrNew(['user_id'=>$use->id]);
         //    $wallet->amount = $inpt['coin'];
         //    $wallet->save();
         // }

         

         // $tran = new Transaction;
         // $tran->tran_id = rand(1000000000,9999999999);
         // $tran->tran_type = 'Admin to user';
         // $tran->amount = $inpt['coin'];
         // // $tran->user_id = $user_data->id;
         // $tran->credit = $use->id;
         // $tran->debit = '4';
         // $tran->save();

         return array('status'=>200,'message'=>'Coin Added Successfully!','data'=>'ok');
      }else{
         return array('status'=>400,'message'=>'User not found');
      } 
   }

  


}
