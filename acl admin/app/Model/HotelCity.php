<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class HotelCity extends Model
{
	protected $table = 'hotel_city';
	protected $fillable = ['name','countrycode','cityid'];

	
}
