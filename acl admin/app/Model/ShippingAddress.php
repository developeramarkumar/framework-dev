<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
	protected $table = 'shipping_address';
	protected $fillable = ['user_id','firstname','lastname','email','mobile_no','country_id','state_id','city_id','postal_code','status'];

	

}
