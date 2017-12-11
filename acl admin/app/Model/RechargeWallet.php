<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class RechargeWallet extends Model
{
	 protected $table = 'recharge_wallets';
	protected $fillable = ['user_id','amount'];

	
}
