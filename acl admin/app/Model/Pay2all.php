<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Pay2all extends Model
{
	protected $table = 'pay2all_hotels';
	protected $fillable = ['hotels','hotel1','hotel2'];
	
}
