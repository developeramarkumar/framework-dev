<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;
class Myplan extends Model
{
	protected $table = 'user_packages';
	protected $fillable = ['user_id','amount','plan_id'];
}

?>