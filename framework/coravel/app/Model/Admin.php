<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
	protected $table = 'ib_users';
	protected $fillable = ['name','username','email','contact','whatsup','password','usertype','status','image','lastvisitDate'];
	
}