<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class ShoppingOrder extends Model
{
	 protected $table = 'shopping_order';
	protected $fillable = ['user_id','product_id','qty','price'];

	

	public function userdetail(){
		return $this->belongsTo(User::class,'user_id','id');
	}

	public function Productdetail(){
		return $this->belongsTo(Product::class,'product_id','id');
	}

	public function productImage(){
		return $this->hasMany(ProductImage::class,'product_id','product_id')->where('thumbnail_status','=', 1);

	}


}
