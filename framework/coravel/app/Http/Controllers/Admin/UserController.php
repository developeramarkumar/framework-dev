<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\User;
use core\Auth;
use \Request;
class UserController extends Controller
{
	
   public function Update($id, Request $request){
   	$data = User::find($id);
   	$data->name = Request::input('f_name');
   	$data->mobile = Request::input('ph');
   	$data->email = Request::input('email');
   	// $data->refer_id = Request::input('email');
   	$data->save();
   	return \Redirect::back();
   	 // dd(Request::input());
   	 
   }

   public function UserLogin($id){

   
// dd($_SESSION);

      $user = User::find($id);

      Auth::guard('user')->login($user);

      // return Auth::guard('user')->user();
      return \Redirect::to('../dashboard');
   }

   public function getList(){

      $requestData = \Request::input();

      $columns = array( 
      // datatable column index  => database column name
         0  =>'name', 
         1  => 'email',
         2  => 'mobile',
         3  => 'ref_id',
         4  => 'user_ref',
         5  => 'created_at',
      );

      $data = User::all();

      $totalData = $data->count();


      $json_data = array(
         "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
         "recordsTotal"    => intval( $totalData ),  // total number of records
         "recordsFiltered" => intval( $totalData ), // total number of records after searching, if there is no searching then totalFiltered = totalData
         "data"            => $data   // total data array
      );

      echo json_encode($json_data);  // send data as json format
   }

   public function tranDestroy($id){
      return $id;
   }
}
