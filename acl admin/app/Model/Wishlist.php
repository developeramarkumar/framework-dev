<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
	// protected $table = 'kycs';
	protected $fillable = ['user_id','product_id'];
	
}
