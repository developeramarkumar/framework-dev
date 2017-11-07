<?php
namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;

use \Request;
use \Redirect;
use App\Model\Operator;
use App\Model\PayBillRecharge;
use Core\Auth;

class PaymentController extends Controller
{

    public function index(){
      
    } 
    public function pay(){
    	$paybillrecharge = new  PayBillRecharge;
       	$paybillrecharge->user_id = Auth::guard('user')->user()->id;
       	$paybillrecharge->operator_id = Request::input('operator_id');
       	$paybillrecharge->number = (string) Request::input('number');
       	$paybillrecharge->amount = Request::input('amount');
        $paybillrecharge->account = Request::input('account');
       	if ($paybillrecharge->save()) {
       		return $this->json(['class'=>'success','message'=>'','data'=>['id'=>\Crypt::encrypt($paybillrecharge->id)]]);
       	}
       	return $this->json(['class'=>'error','message'=>'somthing wrong ! try again.'],500);
    }   
}
