<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\User;
use \Request;
use Core\Auth;
use \App\Model\LiveRate;

class UserWalletDetailsController extends Controller
{

	public function userWalletDetails()
		{
			$user = Auth::api();
			$liveRate = LiveRate::orderBy('id','DESC')->first()->live_rate; 			
			$rewalletamount = $user->redWallet->amount;			
			$coinWallet = $user->greenWallet->amount;			
			$shoppingWallet = (($user->shopping_wallets)?$user->shopping_wallets->amount:'0.00');			
			$rechargeWallets = (($user->recharge_wallets)?$user->recharge_wallets->amount:'0.00');
			$planAmount = (($user->myplan)?$user->myplan->amount:'0.00');
			$UserPlan=(($user->packageUser)?$user->packageUser->plan->name:'Free');
			$username = $user->name;
			$userWallet = $user->wallet_address;
			return array('status'=>200,'message'=>'Success',
			
			'data'=>[
			'LiveRate'=>$liveRate,
			'RedWalletAmount'=>$rewalletamount,
			'CoinWallet'=>$coinWallet,
			'ShoppingWallet'=>$shoppingWallet,
			'RechargeWallet'=>$rechargeWallets,
			'UserName'=>$username,
			'UserPlan'=>$UserPlan,
			'UserPlanAmount'=>$planAmount,
			'UserWalletAddress'=>$userWallet
			]
			);
		}
}