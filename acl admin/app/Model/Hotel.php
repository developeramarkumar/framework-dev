<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
	protected $table = 'hp_hotel_order';
	protected $fillable = ['hotel_details','contact_details','hotel_info','amount','payment_status','booking_status','booking_status_response','user_id','order_date'];
	
}
