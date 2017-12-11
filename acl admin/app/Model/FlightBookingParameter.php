<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class FlightBookingParameter extends Model
{
	protected $table = 'flightBookingParameter';

	protected $fillable = array('booking_id');	
}
