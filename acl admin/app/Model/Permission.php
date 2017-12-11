<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	public function permission(){
		return $this->hasOne(PermissionRole::class,'permission_id','id');
	}
	public function menu(){
		return $this->hasOne(Menu::class,'id','menu_id');
	}
	
}