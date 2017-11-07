<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class LiveRate extends Model
{
	protected $table = 'liverate';
	protected $fillable = ['id','live_rate'];

	
}
