<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class Franchisee extends Model
{
	protected $table = 'franchise_request';
	protected $fillable = ['fullname','mobile','email','message'];
	
}