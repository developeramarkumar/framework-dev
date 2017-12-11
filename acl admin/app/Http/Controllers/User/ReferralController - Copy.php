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
	function get_direct_referral($level = null, $id = null)
	{
	 $inpt = Request::all();
		
    
	 	$userid = $inpt['userid'];

		// $result =\DB::select("select  a.refer_id,a.id,a.name,a.email  FROM `users` as a  inner join `user_packages` as c on  a.id=c.user_id where `refer_id` = '$userid'");
    //dd($result);
		return $result =\DB::select("SELECT * FROM `users` where  id='$userid'");
		
		//while($rows = mysql_fetch_array($result))
		 foreach($result as $rows)
		 {
// $rows->

		$pid=$rows->used_refer;
		if($level==$inpt['id'])
		{
		



		echo'<td>'. ucfirst($rows->name).'</td>';
		echo'<td>'. $rows->refer_id.'</td>';

		// echo'<td>'. $rows['mobile'].'</td>';
		// echo'<td>'. $rows['created_at'].'</td>';
		if($rows->status==1)
		{
		echo'<td> Active </td>';
		}
		else 
		{
		echo'<td> Deactive </td>';
		}
		echo "</tr>";



		//echo " level "."$level-- >"."Parent of -->". $pid." is-->$userid"."<br>";

		  } 

		
		}
		
		$this->get_direct_referral($level+1,$pid);

	}
}
