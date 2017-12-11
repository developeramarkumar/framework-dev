<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $table = 'transction';
	protected $fillable = ['tran_id','tran_type','amount','user_id','credit','debit','coin_val','tran_charge_per','tran_charge','fixed_investment'];

	public function user(){
		return $this->belongsTo(User::class,'user_id','id');
	}

	public function crUser(){
		return $this->belongsTo(User::class,'credit','id');
	}

	public function drUser(){
		return $this->belongsTo(User::class,'debit','id');
	}
	
}
