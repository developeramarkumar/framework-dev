<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class SelfGenerate extends Model
{
	protected $fillable = ['user_id'];	

	public function user(){
		return $this->belongsTO(User::class,'user_id','id');
	}
}
