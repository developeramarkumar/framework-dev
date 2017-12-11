<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	protected $fillable = ['table_name'];
	public function childs(){
		return $this->hasMany(Menu::class,'id','parent');
	}
	public function breads(){
		return $this->hasMany(Permission::class,'menu_id','id');
	}
	public function permission(){
		return $this->hasOne(Permission::class,'menu_id','id');
	}
	// public function permissionRole(){
	// 	return $this->hasManyThrough(PermissionRole::class,'permission_id','id');
	// }
	// public function browse()
 //    {
 //        return $this->hasOne(PermissionRole::class,permission_)->where('browse', 1);
 //    }

 //    public function read()
 //    {
 //        return $this->rows()->where('read', 1);
 //    }

 //    public function edit()
 //    {
 //        return $this->rows()->where('edit', 1);
 //    }

 //    public function add()
 //    {
 //        return $this->rows()->where('add', 1);
 //    }

 //    public function delete()
 //    {
 //        return $this->rows()->where('delete', 1);
 //    }

}