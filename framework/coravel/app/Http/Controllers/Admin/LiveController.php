<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\LiveRate;
use App\Model\EthBusiness;
use App\Model\BtcBusiness;
use App\Model\InrBusiness;
use App\Model\IcoExtra;
use core\Auth;
use \Request;
class LiveController extends Controller
{
	
   public function LiveRate(){

   	$inpt = \Request::all();

      $ra = new LiveRate;
      $ra->live_rate = $inpt['rate'];
      $ra->save();

   	return \Redirect::back();
   	 
   }

   public function EthBussinus(){

      $inpt = \Request::all();

      $ra = new EthBusiness;
      $ra->value = $inpt['rate'];
      $ra->save();

      return \Redirect::back();
   }

   public function BthBussinus(){

      $inpt = \Request::all();

      $ra = new BtcBusiness;
      $ra->value = $inpt['rate'];
      $ra->save();

      return \Redirect::back();
   }

   public function InrBussinus(){

      $inpt = \Request::all();

      $ra = new InrBusiness;
      $ra->value = $inpt['rate'];
      $ra->save();

      return \Redirect::back();
   }

   public function IcoExtra(){

      $inpt = \Request::all();

      $ico = icoExtra::firstOrCreate(['id'=>'1']);
      $ico->ico_start = $inpt['ico_start'];
      $ico->ico_end = $inpt['ico_end'];
      $ico->pre_booking_start = $inpt['booking_start'];
      $ico->pre_booking_end = $inpt['booking_end'];
      $ico->pre_lunching_start = $inpt['lunching_start'];
      $ico->pre_lunching_end = $inpt['lunching_end'];

      $ico->save();

      return \Redirect::back();
   }

  


}
