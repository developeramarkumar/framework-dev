<?php
namespace model;
use \Illuminate\Database\Eloquent\Model;
class User extends Model
{
	protected $table = 'users';
	public function abc(){
		return $this->hasOne('model\Order','user_id','id');
	}
}