<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
	protected $table = 'ib_operator';

	public function service(){
		return $this->belongsTo(Service::class,'id','service_id');
	}
	
}
