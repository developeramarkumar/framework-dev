<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Product;
use core\Auth;
use Core\Session;
use \Request;
use Intervention\Image\ImageManagerStatic as Image;
class ProductController extends Controller
{
	
   public function create(Request $request){
      $validate = $this->validate(Request::Input(),[
         'catgory_id' => 'required',
         'sub_catgory_id'=>'required|numeric',
         'name'=>'required',
         'status'=>'required',
         'mrp'=>'required',
         'price'=>'required',
         'cash_back'=>'required',
         'busi_value'=>'required',
         'franch_point'=>'required',
         'spec_price'=>'required',
         'meta_title'=>'required',
         'meta_desc'=>'required',
      ]);
      if ($validate) {
         return \Redirect::back();
      }

      $inpt = \Request::all();
      
      $pro = new Product;

      if (Request::file('image')) {
         $pro->image = $image = str_replace(' ','-',Request::input('name')).str_random(6).'.'.Request::file('image')->extension();
         Image::make(Request::file('image'))->save('../image/catelog/product/'.$image);
      }

      $pro->category_id = $inpt['catgory_id'];
      $pro->subcategory_id = $inpt['sub_catgory_id'];
      $pro->name = $inpt['name'];
      $pro->desc = $inpt['desc'];
      $pro->slug = $inpt['name'];
      $pro->status = $inpt['status'];
      $pro->mrp = $inpt['mrp'];
      $pro->price = $inpt['price'];
      $pro->cash_back = $inpt['cash_back'];
      $pro->busi_value = $inpt['busi_value'];
      $pro->franch_point = $inpt['franch_point'];
      $pro->special_price = $inpt['spec_price'];
      $pro->meta_title = $inpt['meta_title'];
      $pro->meta_desc = $inpt['meta_desc'];      

      if ($pro->save()) {

         Session::withFlash(['message'=>'Producct added successfuly!','class'=>'success']);     } 
      else {

         Session::withFlash(['message'=>'Sorry something went wrong try again!','class'=>'error']);
      }
      

      return \Redirect::back();
   }

 public function update(Request $request){
    $pro = Product::find(Request::input('id'));
    

     $inpt = \Request::all();
   
     if (Request::file('image')) {
         $pro->image = $image = str_replace(' ','-',Request::input('name')).'.'.Request::file('image')->extension();
         Image::make(Request::file('image'))->save('../image/catelog/product/'.$image);
      }

      $pro->category_id = $inpt['catgory_id'];
      $pro->subcategory_id = $inpt['sub_catgory_id'];
      $pro->name = $inpt['name'];
      $pro->desc = $inpt['desc'];
      $pro->slug = $inpt['name'];
      $pro->status = $inpt['status'];
      $pro->mrp = $inpt['mrp'];
      $pro->price = $inpt['price'];
      $pro->cash_back = $inpt['cash_back'];
      $pro->busi_value = $inpt['busi_value'];
      $pro->franch_point = $inpt['franch_point'];
      $pro->special_price = $inpt['spec_price'];
      $pro->meta_title = $inpt['meta_title'];
      $pro->meta_desc = $inpt['meta_desc'];      

      if ($pro->save()) {

         Session::withFlash(['message'=>'Product updated successfuly!','class'=>'success']);     } 
      else {

         Session::withFlash(['message'=>'Sorry something went wrong try again!','class'=>'error']);
      }
      

      return \Redirect::back();
   }


   public function uploadImage(Request $request){
      $file = $_FILES['file'];
      dd($file);
      foreach ($file as $files) {
         return count($files);
      }
   }










}
