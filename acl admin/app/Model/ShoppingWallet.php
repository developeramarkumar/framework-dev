<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class ShoppingWallet extends Model
{
	 protected $table = 'shopping_wallets';
	protected $fillable = ['user_id','amount','title'];

	
}
