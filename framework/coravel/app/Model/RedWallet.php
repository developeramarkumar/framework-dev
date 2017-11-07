<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class RedWallet extends Model
{
	protected $table = 'red_wallet';
	protected $fillable = ['user_id','amount'];
	
}