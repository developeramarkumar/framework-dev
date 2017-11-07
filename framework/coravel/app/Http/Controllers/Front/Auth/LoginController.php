<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	public static function init(){
		return new CoinController();
	}
    
}
