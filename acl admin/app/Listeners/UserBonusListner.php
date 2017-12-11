<?php
namespace App\Listeners;

use \App\Events\UserBonusEvent;
use \App\Model\WalletLog;
use \App\Model\RedWallet;

class UserBonusListner
{
    /**
     * @param TestEvent $event
     */
    public function handle(UserBonusEvent $event)
    {
    	if ($event->type == 'PaidRefBonus') {
    		$this->PaidRefBonus($event->user);			
    	}
    }



    private function PaidRefBonus($user){
    		$arrData = array_flatten($user->userRefers->toArray());
			$avilable = array_where($arrData, function ($value, $key) use ($user) {
			    if($value == $user->id && $key <= 29){
			      return true;
			    }
			});
			if ($avilable) {
			  	if ($user->mainWallet->package_name) {
				    $bonus =  (($user->mainWallet->package_name/100)*10);
				    $user->usedRefUser->balance = ($user->usedRefUser->balance+$bonus);
				    $user->usedRefUser->save();				    
				    RedWallet::updateOrCreate(['user_id'=>$user->usedRefUser->id],['amount'=>$bonus]);
				    WalletLog::create(['tran_id'=>str_shuffle('abcd1234'),'tran_type'=>'Paid Referral Bonus','wallet'=>$bonus,'credit'=>$user->usedRefUser->id,'debit'=>$user->id,'wallet_date'=>date('d-m-Y')]);
			  	}
			}
    }
    
}