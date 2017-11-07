<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Category;
use \Request;
use Core\Auth;


class GetCategoryController extends Controller
{

	public function GetCategory()
		{
			 $Category = Category::select('id as CategoryId','name as Category')->where('parent', '=', null)->get();
			return array('status' => 200,'data'=>$Category);
			
		}
}