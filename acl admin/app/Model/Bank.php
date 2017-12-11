<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
	protected $table = 'banks';
	protected $fillable = ['user_id','bankname','branchname','account_holder_name','account_no','ifsc_code','nominee_name','nominee_relation','nominee_address','pancard','status'];

	
}

