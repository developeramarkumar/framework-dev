<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
	protected $table = 'ib_roles';
	protected $fillable = ['role_name'];
	
}