<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Ibtopup extends Model
{
	protected $table = 'ib_topup';
	protected $fillable = ['user_id'];
	
}

