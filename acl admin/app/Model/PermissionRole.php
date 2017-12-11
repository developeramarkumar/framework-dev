<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
	// public function permissionRole(){
	// 	return $this->hasOne(Permission::class,'id','permission_id');
	// }
	public function permission(){
		return $this->belongsTo(Permission::class,'permission_id','id');
	}
	
	
}