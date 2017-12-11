<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Hotel;
class HotelController extends Controller
{
    public function hotelLising(Request $request)
    {
    	echo '<pre>';print_r($request);
    }
}
