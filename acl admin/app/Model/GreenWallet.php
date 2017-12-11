<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class GreenWallet extends Model
{
	protected $table = 'green_wallet';
	protected $fillable = ['user_id','amount'];
}
