<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class MonyTransWallet extends Model
{
	protected $table = 'mony_transfer_wallets';
	protected $fillable = ['user_id','image','title'];
	
}
