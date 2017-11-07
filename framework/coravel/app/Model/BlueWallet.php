<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class BlueWallet extends Model
{
	protected $table = 'blue_wallet';
	protected $fillable = ['user_id','amount'];
}
