<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;

use App\Model\Transaction;
use Redirect;
use Core\Session;
use App\Model\User;
use App\Model\ShoppingPayment;
use Core\Auth;


class PaymentController extends Controller
{

    // public function __construct()
    // {
    //     // $this->middleware('App\Http\Middleware\Api\RedirectIfNotAuthenticated');
    // }

    public function pay(Request $request){

      // $api = new \Instamojo\Instamojo(API_KEY, AUTH_TOKEN);
      // $api = new \Instamojo\Instamojo('77e6d54d7ab76ced05282d73e856d96e','a583e15de43ee8650d4bb0374f4eb964');
      $api = new \Instamojo\Instamojo('21a0a4d442feff875849bce67ea039cb','aa9f5b793c8a22c8446104a016809e1b');

      try {
          $response = $api->paymentRequestCreate(array(
              "purpose" => "shopping payment",
              "amount" => "100",
              "send_email" => false,
              "email" => "saksham@happypesa.com",
              "redirect_url" => "http://10.107.4.8/cashcoin/app/confirm-payment",
              // "redirect_url" => "http://10.104.4.8/cashcoin"
          ));
          // print_r($response); exit();
          $payment = new ShoppingPayment;
          $payment->user_id = Auth::guard('user')->user()->id;
          $payment->pay_id = $response['id'];
          $payment->phone = $response['phone'];
          $payment->email = $response['email'];
          $payment->buyer_name = $response['buyer_name'];
          $payment->amount = $response['amount'];
          $payment->purpose = $response['purpose'];
          $payment->expires_at = $response['expires_at'];
          $payment->status = $response['status'];
          $payment->send_sms = $response['send_sms'];
          $payment->send_email = $response['send_email'];
          $payment->sms_status = $response['sms_status'];
          $payment->email_status  = $response['email_status'];
          $payment->shorturl  = $response['shorturl'];
          $payment->longurl  = $response['longurl'];
          $payment->redirect_url  = $response['redirect_url'];
          $payment->webhook  = $response['webhook'];
          $payment->save();
          // return $payment;
          if ($payment) {
            return  \Redirect::to($response['longurl']);
          }else{
            // return \Redirect::back();
          }
          echo "<pre>";
          print_r($response['id']);
          try {
              $response = $api->paymentRequestStatus($response['id']);
              print_r($response);
          }
          catch (Exception $e) {
              print('Error: ' . $e->getMessage());
          }
      }
      catch (Exception $e) {
          print('Error: ' . $e->getMessage());
      }

    } 

    public function PaymentResponse(){
      $inpt = \Request::all();
      $api = new \Instamojo\Instamojo('21a0a4d442feff875849bce67ea039cb','aa9f5b793c8a22c8446104a016809e1b');
      try {
          $response = $api->paymentRequestStatus('d108b8d50dd34a219a613d2893bcd46d');
          // print_r($response);
      }
      catch (Exception $e) {
          print('Error: ' . $e->getMessage());
      }


      $data = ShoppingPayment::where('pay_id',$inpt['payment_request_id'])->first();
      // $data->status = $response['payments']['status'];
      // $data->save();
      // $response = $api->paymentRequestPaymentStatus('MOJO7b16005A67523742','60093a0f9ec64240877e1826bb44241f');
      dd($response['payments']->status);
    }   

    // public function Response(Request $request){
    //   // return \Request::all();
    //   // $api = new \Instamojo\Instamojo('21a0a4d442feff875849bce67ea039cb','aa9f5b793c8a22c8446104a016809e1b');
    //   // try {
    //   //     $response = $api->paymentRequestStatus('60093a0f9ec64240877e1826bb44241f');
    //   //     print_r($response);
    //   // }
    //   // catch (Exception $e) {
    //   //     print('Error: ' . $e->getMessage());
    //   // }


    //   // exit();

    //   // $response = $api->paymentRequestPaymentStatus('MOJO7b16005A67523742','60093a0f9ec64240877e1826bb44241f');
    //   // dd($response);
    // }

}
