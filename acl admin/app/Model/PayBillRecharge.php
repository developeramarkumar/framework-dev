<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class PayBillRecharge extends Model
{
	protected $table = 'bill_payment_and_recharge';
	protected $fillable = ['user_id','operator_id','number','amount','status','cyber_status'];
	
}