<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;

use App\Model\Transaction;
use Redirect;
use Core\Session;
use App\Model\User;
use App\Model\Support;
use Core\Auth;
use App\Model\Franchise;

class HelpDeskController extends Controller
{
    public function store(Request $request){

      $inp = Request::input();
      $sup = new Support;
      $sup->user_id = Auth::guard('user')->user()->id;
      $sup->subject = $inp['subject'];
      $sup->message = $inp['message'];
      $sup->save();

      if ($sup) {
        Session::withFlash(['message'=>'Your request has been receved!','class'=>'success']);
        return Redirect::back();
      }else{
        Session::withFlash(['message'=>'Sorry something went wrong !','class'=>'error']);
        return Redirect::back();
      }
    }

    public function destroy($id){
      $data = Support::find($id);
      if ($data) {
        $data->delete();
        return Redirect::back();
      }
      return Redirect::back();
    }
    public function frenchiesRequest(){
        // dd(Request::all());
        $validate = $this->validate(Request::all(),[
            'name'=>'required',
            'mobile'=>'required',
            'email'=>'required',
        ]);
        if ($validate) {
            Session::withInput(Request::all());
            Session::withError($validate);
            return Redirect::back();
        }
        $franchise = new Franchise();
        $franchise->fullname = Request::input('name');
        $franchise->mobile = Request::input('mobile');
        $franchise->email = Request::input('email');
        $franchise->bussiness_identity = Request::input('bussiness');
        $franchise->location = Request::input('location');
        if ($franchise->save()) {
            Session::withFlash(['message'=>'Your request has been sent!','class'=>'success']);
            return Redirect::back();
        }else{
            Session::withFlash(['message'=>'Sorry something went wrong !','class'=>'error']);
            return Redirect::back();
        }
    }
}
