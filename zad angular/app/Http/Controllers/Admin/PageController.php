<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;
class PageController extends Controller
{
    //
    public function index(Request $request,$page){
    	$dir = null;
    	foreach ($request->all() as $key => $value) {
    		$dir .= $value.'/';
    	}
    	return Storage::disk('adminResource')->get($dir.$page);
    }
}
