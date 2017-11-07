<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = ['user_id','data','status','order_prefix','plan_id','usd_amount'];
	
}
