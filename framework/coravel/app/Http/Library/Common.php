<?php 
namespace App\Http\Library;

use App\Model\SelfGenerate;
use App\Model\User;
/**
* Common library for use inside all of application;
*/
class Common
{
	
	public static function SelfGenTime($id){
		$user = User::find($id);
		$self = $user->selfGenerate;
		if (!$self) {
			return 1;
		}else{
			$time = new \Carbon\Carbon($self->last_generate);
            $now = \Carbon\Carbon::now();
            $hour = $time->diffInHours($now);
            if ($hour>24) {
            	return 1;
            }else{
            	return 0;
            }
		}

	}
}