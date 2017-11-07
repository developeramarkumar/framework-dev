<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\News;
use core\Auth;
use \Request;
class NewsController extends Controller
{
	
   public function Update($id, Request $request){
   	$data = News::find($id);
   	$data->title = Request::input('title');
   	$data->description = Request::input('description');
   	$data->type_news = Request::input('type_news');
   	// $data->refer_id = Request::input('email');
   	$data->save();
   	return \Redirect::back();
   	 // dd(Request::input());
   	 
   }

  


}
