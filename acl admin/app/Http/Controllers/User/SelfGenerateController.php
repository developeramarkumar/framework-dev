<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;
use App\Model\User;
use App\Model\SelfGenerate;
use App\Model\RedWallet;
use App\Model\Ibplan;
use App\Model\Transaction;
use Redirect;
use Core\Session;
use Core\Auth;

class SelfGenerateController extends Controller
{
    public function selfGenerate(){
        $id = Auth::guard('user')->user()->topUp->first();
        $self = Auth::guard('user')->user()->selfGenerate;
        if ($id) {

            $id = $id->plan_id;
            $plan = Ibplan::where('ib_plan_id',$id)->first();

            $coin = ($plan->amount*$plan->plan_percentage)/100;

            $red = RedWallet::where(['user_id'=>Auth::guard('user')->user()->id])->first();

            if (!$self) {

                if ($red) {
                    $red->amount = $red->amount+$coin;  
                    $red->save();                
                }else{
                    $red = new RedWallet;
                    $red->user_id = Auth::guard('user')->user()->id;
                    $red->amount = $coin;
                    $red->save();                
                }
                $selGen = SelfGenerate::firstOrNew(['user_id'=>Auth::guard('user')->user()->id]);
                $selGen->last_generate = \Carbon\Carbon::now();
                $selGen->save();
                
                $tran = new Transaction;
                $tran->tran_id = rand(100000000,999999999);
                $tran->tran_type = 'Daily Bonus';
                $tran->amount = $coin;
                $tran->credit = Auth::guard('user')->user()->id;
                $tran->debit = '1';
                $tran->save();

            }else {
                $time = new \Carbon\Carbon($self->last_generate);
                $now = \Carbon\Carbon::now();
                $hour = $time->diffInHours($now);
                if ($hour>24) {
                    if ($red) {
                        $red->amount = $red->amount+$coin;  
                        $red->save();                
                    }else{
                        $red = new RedWallet;
                        $red->user_id = Auth::guard('user')->user()->id;
                        $red->amount = $coin;
                        $red->save();                
                    }

                    $selGen = SelfGenerate::firstOrNew(['user_id'=>Auth::guard('user')->user()->id]);
                    $selGen->last_generate = \Carbon\Carbon::now();
                    $selGen->save();

                    $tran = new Transaction;
                    $tran->tran_id = rand(100000000,999999999);
                    $tran->tran_type = 'Daily Bonus';                    ;
                    $tran->amount = $coin;
                    $tran->credit = Auth::guard('user')->user()->id;
                    $tran->debit = '1';
                    $tran->save();
                }
            }
            Session::withFlash(['message'=>'Your self generate complete','class'=>'success']);
            return Redirect::back();

        } else {
            Session::withFlash(['message'=>'You are not able to generate','class'=>'error']);
            return Redirect::back();
            
        }
        
        
    }
}
