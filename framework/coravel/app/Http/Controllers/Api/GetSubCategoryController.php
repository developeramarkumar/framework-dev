<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Category;
use \Request;
use Core\Auth;


class GetSubCategoryController extends Controller
{

	public function GetSubCategory()
		{
			
			$catid = \Request::input('catid');
			$Category = Category::select('id as SubCategoryId','name as SubCategory')->where('parent', '=', $catid)->get();
			return array('status' => 200,'data'=>$Category);
		}
}