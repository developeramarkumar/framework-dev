<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class HotelCountry extends Model
{
	protected $table = 'hotel_country';	
	protected $fillable = ['name','countrycode'];
}
