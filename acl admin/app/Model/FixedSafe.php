<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class FixedSafe extends Model
{
	protected $table = 'fixed_zone_wallet';
	protected $fillable = ['id','user_id','amount','fixed_investment'];
	
}