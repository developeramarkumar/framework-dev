<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	// protected $table = 'products';
	protected $fillable = ['user_id','image','title'];

	public function setSlugAttribute($value)
	{
	    $temp = str_slug($value, '-');
	    if(!Product::all()->where('slug',$temp)->isEmpty()){
	        $i = 1;
	        $blogslug = $temp . '-' . $i;
	        while(!Product::all()->where('slug',$blogslug)->isEmpty()){
	            $i++;
	            $blogslug = $temp . '-' . $i;
	        }
	        $temp =  $blogslug;
	    }
	    $this->attributes['slug'] = $temp;
	}

	public function category(){
		return $this->belongsTo(ProductCategory::class,'category_id','id');
	}

	public function subCategory(){
		return $this->belongsTo(ProductCategory::class,'subcategory_id','id');
	}

	public function productImage(){
		return $this->hasMany(ProductImage::class,'product_id','id')->where('thumbnail_status','=', 1);
	}

}
