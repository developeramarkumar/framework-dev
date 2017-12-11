<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public function permissions(){
		return $this->hasMany(PermissionRole::class)->select('permission_id');
	}
	
}