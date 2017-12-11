<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
	public function category(){
		return $this->belongsTo(Category::class,'category_id','id');
	}	
	public function tagname(){
		return $this->belongsTo(Tags::class,'name','id');
	}	
}