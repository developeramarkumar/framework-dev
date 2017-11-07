<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use SoftDeletes;
  	protected $table = 'product_category'; 
    // public function childMenu(){
    // 	return $this->hasMany('App\Model\Category','id','childrens');
    // }
   // protected $table = 'beneficiary';
	//protected $fillable = ['id','ukey','name','title','parent'];
}