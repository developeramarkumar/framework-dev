<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
	protected $table = 'kycs';
	protected $fillable = ['user_id','image','title'];

	
}
