<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class FlightAdultPassenger extends Model
{
	protected $table = 'flightAdultPassenger';	

	protected $fillable = array('booking_id');
}
