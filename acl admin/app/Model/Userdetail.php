<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Userdetail extends Model
{
	protected $table = 'user_detail';
	protected $fillable = ['user_id','gender','dob','city','state','transaction_pass','postal_code','profile_photo','whats_number'];

	
}