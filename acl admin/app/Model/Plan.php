<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	protected $table = 'ib_plan';
	protected $fillable = ['ib_plan_id','name','amount'];
	//protected $fillable = ['name','amount','plan_percentage'];

	public function user(){
		return $this->belongsTo(User::class,'user_id','id');
	}

	
	
}
