<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
	protected $table = 'user_packages';
	protected $fillable = ['user_id','amount','plan_id'];
	
	public function plan(){
		return $this->belongsTo(Plan::class,'plan_id','id');
	}
}
