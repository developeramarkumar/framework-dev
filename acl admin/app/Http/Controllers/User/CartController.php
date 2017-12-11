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
use App\Model\Product;
use App\Model\Wishlist;
use App\Model\ShippingAddress;
// use Darryldecode\Cart;
use App\Http\Library\Cart;


class CartController extends Controller
{
    public function add($id){

      $pro = Product::find($id);



      $cart = new Cart;

      

      $cart->insert(array(
        'id'    =>  $pro->id,
        'name'  =>  $pro->name,
        'price' =>  $pro->price,
        'qty'   =>  1,
      ));

      return $cart->contents();

    }

    public function addCartWeb($id){
      $pro = Product::find($id);



      $cart = new Cart;

      

      $cart->insert(array(
        'id'    =>  $pro->id,
        'name'  =>  $pro->name,
        'price' =>  $pro->price,
        'qty'   =>  1,
      ));

      return \Redirect::to('../cart');
    }

    public function Update(Request $request){
      $inpt = \Request::all();
      $update = new Cart;

      $update->update(array('rowid'=>$inpt['rowid'],'qty'=>$inpt['qty']));
      return $update->contents();
      
    }

    public function remove($id){

      $cart = new Cart;

      $cart->remove($id);

      return \Redirect::back();

    }

    public function wishList($id){
      // $pro = Product::find($id);
      $wish = Wishlist::firstOrNew(['user_id'=>Auth::guard('user')->user()->id,'product_id'=>$id]);
      $wish->status = '0';
      $wish->qty = (($wish)?$wish->qty+1:1);
      $wish->save();

      return array('status'=>200,'message'=>'success');
    }

    public function checkout(Request $request){
      // return 'ok';
      return $bill = \Request::input('billing');
      $ship = \Request::input('shipping');

      $ship = new ShippingAddress;
      $ship->user_id = Auth::guard('user')->user()->id;
      $ship->firstname = $bill['fname'];
      $ship->lastname = $bill['lname'];
      $ship->email = $bill['lname'];
      $ship->mobile_no = $bill['lname'];
      $ship->lastname = $bill['lname'];

            
    }
}
