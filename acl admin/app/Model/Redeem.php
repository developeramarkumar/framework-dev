<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
	protected $fillable = ['user_id'];

	public function user(){
		return $this->belongsTo(User::class,'user_id','id');
	}
	

}


