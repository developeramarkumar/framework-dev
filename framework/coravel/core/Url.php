<?php  
namespace Core;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Auth\Guard;
class Url 
{
	public static function to(){
		return new Application();
	}
}
?>