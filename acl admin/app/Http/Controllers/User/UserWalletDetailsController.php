<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Core\Event;
use App\Events\SmsEvent;
use App\Model\User;
use App\Model\UserOtp;
use Carbon\Carbon;
use Core\Auth;
use \Request;
use \Session;
use \Redirect;
use \App\Model\LiveRate;
use \Hash;
use \Crypt;

class UserWalletDetailsController extends Controller
{
    public function UserDetails()
	{
		$liveRate = LiveRate::orderBy('id','DESC')->first()->live_rate; 		
		$rewalletamount = $user->redWallet->amount;			
		$coinWallet = $user->greenWallet->amount;			
		$shoppingWallet = (($user->shopping_wallets)?$user->shopping_wallets->amount:'0.00');		
		$rechargeWallets = (($user->recharge_wallets)?$user->recharge_wallets->amount:'0.00');
		
		return array('status'=>200,'message'=>'success','RechargeWallet'=>$rechargeWallets,'ShoppingWallet'=>$shoppingWallet,'CoinWallet'=>$coinWallet,'RedwalletAmount'=>$rewalletamount,'LiveRate'=>$liveRate);
	}
}
