<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
	protected $table = 'beneficiary';
	protected $fillable = ['user_id','sender_id','name','bank_name','beneficiaryid','status','bank_code','account','ifsc'];
	
}