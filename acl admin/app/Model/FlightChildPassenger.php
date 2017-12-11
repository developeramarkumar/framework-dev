<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class FlightChildPassenger extends Model
{
	protected $table = 'flightChildPassenger';	

	protected $fillable = array('booking_id');
}
