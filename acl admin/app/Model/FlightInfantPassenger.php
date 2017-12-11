<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class FlightInfantPassenger extends Model
{
	protected $table = 'flightInfantPassenger';	

	protected $fillable = array('booking_id');
}
