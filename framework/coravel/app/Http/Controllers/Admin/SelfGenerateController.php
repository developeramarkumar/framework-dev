<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Ibplan;
use App\Model\UserPackage;
use App\Model\SelfGenerate;
use App\Model\Transaction;
use App\Model\RedWallet;
use core\Auth;
use App\Http\Library\Common;
use \Request;

class SelfGenerateController extends Controller
{
	
   public function selfGenerate(Request $request){
   	$inpt = Request::input();
      Common::SelfGenTime(2);
      // return UserPackage::all();
      $user = User::whereBetween('id',[$inpt['from'],$inpt['to']])->get(['id']);

      $successList = [];

      foreach ($user as $value) {
         $topUp = UserPackage::where('user_id',$value->id)->first();

         if ($topUp) {
            if (Common::SelfGenTime($value->id)) {
               $successList[] = $value->id;
            }
         }
         
      }
      $package = UserPackage::whereIn('user_id',$successList)->get();

      foreach ($package as $value) {
         if ($value->plan) {
            $plan = $value->plan;
            $coin = ($plan->amount*$plan->plan_percentage)/100;
            $red = RedWallet::where(['user_id'=>$value->id])->first();

            if ($red) {
                 $red->amount = $red->amount+$coin;  
                 $red->save();                
             }else{
                 $red = new RedWallet;
                 $red->user_id = Auth::guard('user')->user()->id;
                 $red->amount = $coin;
                 $red->save();                
             }

            $selGen = SelfGenerate::firstOrNew(['user_id'=>$value->user_id]);
            $selGen->last_generate = \Carbon\Carbon::now();
            $selGen->save();
             
            $tran = new Transaction;
            $tran->tran_id = rand(100000000,999999999);
            $tran->tran_type = 'Daily Bonus';
            $tran->amount = $coin;
            $tran->credit = $value->user_id;
            $tran->debit = '1';
            $tran->save();
         }
      }
      
   	 
   }

  


}
