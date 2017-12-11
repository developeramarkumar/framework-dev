<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
	protected $fillable = ['name','username','email','contact','whatsup','password','usertype','status','image','lastvisitDate'];


	// public function permissionRole(){
	// 	return $this->hasMany(PermissionRole::class,'role_id','role_id');
	// }
	// public function permission(){
	// 	return $this->hasOne(PermissionRole::class,'role_id','role_id');
	// }
	// public function permissions()
 //    {
 //        return $this->belongsToMany(Admin::class,'permission_roles','role_id','role_id');
 //    }
	// public function permission(){
	// 	return $this->hasOne(Permission::class,'id','permission_id');
	// }

	
}