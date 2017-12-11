<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Order;
class PaymentController extends Controller
{
    public function Order(){

    	$tran = new Order;
		$tran->user_id = $_SESSION['reg_id'];
		$tran->order_prefix	 = $order_prefix;
		$tran->save();
    }
}
