<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use \Request;
use App\Model\User;
use App\Model\Userdetail;

use Redirect;
use Core\Session;
use Core\Auth;

class ReferralController extends Controller
{	
	function finduser($user,$count,$dt,$lavel){
		$count++;
		if ($count <= $lavel) {		
			$user2 = User::select(['id','name','email','refer_id'])->whereIn('used_refer',$user)->get();
			if ($user2->count()) {
				$dt[$count]=  $user2;
				$user2 = $user2->toArray();
				$user2 = array_pluck($user2,'id');
				return $this->finduser($user2,$count,$dt,$lavel);
			}		
		}
		if(@$dt[$lavel]){
		 $data = $dt[$lavel];
		return array("status" => "200", "status_message" => "success",'data'=>$data);
		}
		else{
			return array("status" => "400", "status_message" => "no record found");
		}
	}

	function get_direct_referral($level=0, $id = null)
	{
		$inpt = Request::all();
		
		$lavel = $inpt['id'];
		$dt= array();
		$user2 = User::select(['id'])->where('used_refer',Auth::guard('user')->user()->id)->get();
		// dd($user2);
		$user2 = $user2->toArray();
		$user2 = array_pluck($user2,'id');
		return $labels=$this->finduser($user2,0,$dt,$lavel);
		
	
		
		
		
	}
}
