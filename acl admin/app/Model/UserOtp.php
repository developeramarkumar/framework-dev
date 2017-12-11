<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
	protected $table = 'user_otps';
	protected $fillable = ['mobile','otp'];
	
}